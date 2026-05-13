/**
 * Standardized Loading Utilities
 * Domino-style loader animation
 */

const LoadingManager = {
    loadingStartTime: null,
    minimumDisplayTime: 1200, // Show loading for at least 1.2 seconds
    
    /**
     * Show full page loading overlay with domino animation
     * @param {string} message - Main loading message
     * @param {string} submessage - Optional sub-message
     */
    show: function(message = 'Loading...', submessage = '') {
        // Track when loading started
        this.loadingStartTime = Date.now();
        
        // Remove existing overlay if any
        this.hide(true); // Pass true to skip minimum time check
        
        // Ensure keyframes animation is defined
        if (!document.getElementById('dominoKeyframes')) {
            const style = document.createElement('style');
            style.id = 'dominoKeyframes';
            style.textContent = `
                @keyframes dominos {
                    0% {
                        transform: translateX(-50%) rotate(0deg);
                        opacity: 1;
                    }
                    50% {
                        transform: translateX(-50%) rotate(0deg);
                        opacity: 0.7;
                    }
                    75% {
                        transform: translateX(-50%) rotate(90deg);
                        opacity: 0.7;
                    }
                    80% {
                        transform: translateX(-50%) rotate(90deg);
                        opacity: 1;
                    }
                    100% {
                        transform: translateX(-50%) rotate(0deg);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        const overlay = document.createElement('div');
        overlay.id = 'globalLoadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-content">
                <div class="domino-container">
                    <div class="domino-loader" style="animation: dominos 1s ease infinite;"></div>
                    <div class="domino-loader" style="animation: dominos 1s ease infinite;"></div>
                    <div class="domino-loader" style="animation: dominos 1s ease infinite;"></div>
                    <div class="domino-loader" style="animation: dominos 1s ease infinite;"></div>
                    <div class="domino-loader" style="animation: dominos 1s ease infinite;"></div>
                </div>
                <div class="loading-text">${message}</div>
                ${submessage ? `<div class="loading-subtext">${submessage}</div>` : ''}
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        // Force reflow to ensure animation starts
        overlay.offsetHeight;
        
        console.log('LoadingManager: Overlay created with domino animation');
    },
    
    /**
     * Hide full page loading overlay with minimum display time
     * @param {boolean} immediate - Skip minimum display time check
     */
    hide: function(immediate = false) {
        const overlay = document.getElementById('globalLoadingOverlay');
        if (!overlay) {
            // Restore body scroll even if no overlay
            document.body.style.overflow = '';
            return;
        }
        
        // If immediate hide or no start time tracked, hide right away
        if (immediate || !this.loadingStartTime) {
            overlay.remove();
            document.body.style.overflow = '';
            this.loadingStartTime = null;
            return;
        }
        
        // Calculate how long loading has been displayed
        const displayTime = Date.now() - this.loadingStartTime;
        const remainingTime = Math.max(0, this.minimumDisplayTime - displayTime);
        
        // Wait for remaining time before hiding
        setTimeout(() => {
            const overlayCheck = document.getElementById('globalLoadingOverlay');
            if (overlayCheck) {
                overlayCheck.remove();
            }
            document.body.style.overflow = '';
            this.loadingStartTime = null;
        }, remainingTime);
    },
    
    /**
     * Show loading state on a button
     * @param {HTMLElement} button - The button element
     * @param {string} text - Loading text (default: 'Loading...')
     */
    buttonStart: function(button, text = 'Loading...') {
        if (!button) return;
        
        // Store original content
        button.dataset.originalHtml = button.innerHTML;
        button.disabled = true;
        
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            ${text}
        `;
    },
    
    /**
     * Restore button to original state
     * @param {HTMLElement} button - The button element
     */
    buttonStop: function(button) {
        if (!button) return;
        
        button.disabled = false;
        if (button.dataset.originalHtml) {
            button.innerHTML = button.dataset.originalHtml;
            delete button.dataset.originalHtml;
        }
    },
    
    /**
     * Show loading on form submission
     * @param {HTMLFormElement} form - The form element
     * @param {Object} options - Configuration options
     */
    formSubmit: function(form, options = {}) {
        const defaults = {
            message: 'Submitting...',
            submessage: 'Please wait while we process your request.',
            buttonText: 'Submitting...'
        };
        
        const config = { ...defaults, ...options };
        
        if (!form) return;
        
        form.addEventListener('submit', () => {
            // Find submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn) {
                this.buttonStart(submitBtn, config.buttonText);
            }
            
            // Show overlay after short delay
            setTimeout(() => {
                this.show(config.message, config.submessage);
            }, 300);
        });
    },
    
    /**
     * Create inline spinner (Bootstrap compatible)
     * @param {string} size - 'sm', 'md', or 'lg'
     * @param {string} color - Bootstrap color class
     * @returns {string} HTML string for spinner
     */
    inlineSpinner: function(size = 'sm', color = 'success') {
        const sizeClass = size === 'sm' ? 'spinner-border-sm' : '';
        return `<span class="spinner-border ${sizeClass} text-${color}" role="status" aria-hidden="true"></span>`;
    }
};

// Auto-hide loading on page errors
window.addEventListener('error', function() {
    LoadingManager.hide(true); // Immediate hide on error
});

// Make globally available
window.LoadingManager = LoadingManager;
