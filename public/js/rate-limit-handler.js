/**
 * Global Rate Limit Handler
 * Intercepts 429 responses and shows friendly popup with countdown
 */

// Create popup HTML (only once)
if (!document.getElementById('rate-limit-modal')) {
    const modalHTML = `
        <div id="rate-limit-modal" class="rate-limit-modal" style="display: none;">
            <div class="rate-limit-overlay"></div>
            <div class="rate-limit-content">
                <div class="rate-limit-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="rate-limit-title">Too Many Requests</h3>
                <p class="rate-limit-message" id="rate-limit-message">
                    You've made too many requests. Please wait before trying again.
                </p>
                <div class="rate-limit-timer" id="rate-limit-timer">
                    Please wait <span id="countdown-seconds">60</span> seconds
                </div>
                <button class="rate-limit-close-btn" id="rate-limit-close">
                    OK
                </button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Global form submit interceptor
document.addEventListener('DOMContentLoaded', function() {
    // Intercept all form submissions
    document.addEventListener('submit', function(e) {
        const form = e.target;
        
        // Skip if not AJAX form (let normal forms handle it)
        if (!form.classList.contains('ajax-form')) {
            return;
        }
        
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitButton = form.querySelector('[type="submit"]');
        const originalText = submitButton ? submitButton.textContent : '';
        
        // Disable button and show loading
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
        }
        
        fetch(form.action, {
            method: form.method || 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 429) {
                // Rate limit exceeded
                const retryAfter = response.headers.get('Retry-After') || 60;
                showRateLimitModal(retryAfter, form.action);
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                // Handle success/error
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        form.reset();
                        showSuccessMessage(data.message);
                    }
                } else {
                    showErrorMessage(data.message || 'An error occurred');
                }
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showErrorMessage('An error occurred. Please try again.');
        })
        .finally(() => {
            // Re-enable button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    });
    
    // Intercept AJAX requests (for fetch/axios)
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        return originalFetch.apply(this, args).then(response => {
            if (response.status === 429) {
                const retryAfter = response.headers.get('Retry-After') || 60;
                const url = args[0];
                showRateLimitModal(retryAfter, url);
            }
            return response;
        });
    };
});

/**
 * Show rate limit modal with countdown
 */
function showRateLimitModal(retryAfter, action) {
    const modal = document.getElementById('rate-limit-modal');
    const messageEl = document.getElementById('rate-limit-message');
    const timerEl = document.getElementById('rate-limit-timer');
    const countdownEl = document.getElementById('countdown-seconds');
    const closeBtn = document.getElementById('rate-limit-close');
    
    // Determine action type from URL for custom message
    let actionName = 'request';
    if (action.includes('register')) {
        actionName = 'registration';
    } else if (action.includes('verify-email')) {
        actionName = 'verification code submission';
    } else if (action.includes('forgot-password') || action.includes('reset-password')) {
        actionName = 'password reset';
    } else if (action.includes('plant-request') || action.includes('client-request')) {
        actionName = 'inquiry submission';
    }
    
    // Set message
    messageEl.textContent = `You've submitted too many ${actionName} requests. Please wait before trying again.`;
    
    // Convert retry-after to number
    let seconds = parseInt(retryAfter);
    if (seconds > 60) {
        const minutes = Math.ceil(seconds / 60);
        timerEl.innerHTML = `Please wait <span id="countdown-seconds">${minutes}</span> ${minutes === 1 ? 'minute' : 'minutes'}`;
    } else {
        countdownEl.textContent = seconds;
    }
    
    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Start countdown
    const countdownInterval = setInterval(() => {
        seconds--;
        
        if (seconds <= 0) {
            clearInterval(countdownInterval);
            closeRateLimitModal();
        } else if (seconds <= 60) {
            countdownEl.textContent = seconds;
            timerEl.innerHTML = `Please wait <span id="countdown-seconds">${seconds}</span> ${seconds === 1 ? 'second' : 'seconds'}`;
        } else {
            const minutes = Math.ceil(seconds / 60);
            timerEl.innerHTML = `Please wait <span id="countdown-seconds">${minutes}</span> ${minutes === 1 ? 'minute' : 'minutes'}`;
        }
    }, 1000);
    
    // Close button handler
    closeBtn.onclick = function() {
        clearInterval(countdownInterval);
        closeRateLimitModal();
    };
    
    // Close on overlay click
    modal.querySelector('.rate-limit-overlay').onclick = function() {
        clearInterval(countdownInterval);
        closeRateLimitModal();
    };
}

/**
 * Close rate limit modal
 */
function closeRateLimitModal() {
    const modal = document.getElementById('rate-limit-modal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Show success message (helper)
 */
function showSuccessMessage(message) {
    // Create temporary success alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-success';
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; padding: 15px 20px; background: #28a745; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    alert.textContent = message;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

/**
 * Show error message (helper)
 */
function showErrorMessage(message) {
    // Create temporary error alert
    const alert = document.createElement('div');
    alert.className = 'alert alert-danger';
    alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; padding: 15px 20px; background: #dc3545; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    alert.textContent = message;
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
