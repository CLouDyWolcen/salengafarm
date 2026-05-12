/**
 * Global Push Notifications System
 * Provides a simple interface for showing toast notifications
 */

window.PushNotifications = {
    show: function(type, message, autoHide = true) {
        // Create notification container if it doesn't exist
        let container = document.getElementById('globalNotificationContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'globalNotificationContainer';
            container.style.cssText = `
                position: fixed;
                top: 80px;
                right: 20px;
                z-index: 99999;
                max-width: 400px;
                pointer-events: none;
            `;
            document.body.appendChild(container);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'push-notification-item';
        notification.style.cssText = `
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            padding: 16px;
            border-left: 4px solid ${this.getColor(type)};
            animation: slideInRight 0.3s ease-out forwards;
            cursor: pointer;
            pointer-events: auto;
            position: relative;
            overflow: hidden;
            will-change: opacity, transform;
        `;

        // Add countdown bar for auto-hide notifications
        let countdownBar = '';
        if (autoHide) {
            countdownBar = `
                <div style="
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: ${this.getColor(type)};
                    width: 100%;
                    animation: countdown 5s linear forwards;
                "></div>
            `;
        }

        notification.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    background: ${this.getColor(type)};
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                ">
                    <i class="${this.getIcon(type)}" style="color: white; font-size: 12px;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 14px; line-height: 1.4; color: #333;">
                        ${message}
                    </div>
                </div>
                <button class="notification-close-btn" style="
                    background: none;
                    border: none;
                    color: #999;
                    cursor: pointer;
                    padding: 0;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                ">
                    <i class="fas fa-times" style="font-size: 12px;"></i>
                </button>
            </div>
            ${countdownBar}
        `;

        // Add hover effects - store references so we can remove them later
        const hoverInHandler = function() {
            if (!this.dataset.fadingOut) {
                this.style.transform = 'translateX(-5px)';
                this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.2)';
            }
        };

        const hoverOutHandler = function() {
            if (!this.dataset.fadingOut) {
                this.style.transform = 'translateX(0)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            }
        };

        notification.addEventListener('mouseenter', hoverInHandler);
        notification.addEventListener('mouseleave', hoverOutHandler);
        
        // Store handlers on element for later removal
        notification._hoverInHandler = hoverInHandler;
        notification._hoverOutHandler = hoverOutHandler;

        // Close button handler
        const closeBtn = notification.querySelector('.notification-close-btn');
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            startFadeOut(notification);
        });

        // Add to container
        container.appendChild(notification);

        // Auto-hide after 5 seconds if enabled
        if (autoHide) {
            setTimeout(() => {
                if (notification.parentNode) {
                    startFadeOut(notification);
                }
            }, 5000);
        }

        // Add CSS animations if not already added
        if (!document.getElementById('pushNotificationStyles')) {
            const style = document.createElement('style');
            style.id = 'pushNotificationStyles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes countdown {
                    from {
                        width: 100%;
                    }
                    to {
                        width: 0%;
                    }
                }
                
                /* Fade-out classes with maximum specificity */
                .push-notification-item.fading-out {
                    pointer-events: none !important;
                    cursor: default !important;
                    animation: none !important;
                }
                
                .push-notification-item.fading-out-opacity {
                    transition: opacity 0.6s ease !important;
                    opacity: 0 !important;
                    transform: translateX(0) !important;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
                }
                
                .push-notification-item.fading-out-slide {
                    transition: transform 0.4s ease, opacity 0.4s ease !important;
                    transform: translateX(400px) !important;
                    opacity: 0 !important;
                }
            `;
            document.head.appendChild(style);
        }

        return notification;
    },

    getColor: function(type) {
        const colors = {
            'success': '#28a745',
            'danger': '#dc3545',
            'warning': '#ffc107',
            'info': '#17a2b8'
        };
        return colors[type] || colors.info;
    },

    getIcon: function(type) {
        const icons = {
            'success': 'fas fa-check',
            'danger': 'fas fa-exclamation-triangle',
            'warning': 'fas fa-exclamation-circle',
            'info': 'fas fa-info-circle'
        };
        return icons[type] || icons.info;
    }
};

// Fade-out function - separate from the main object to avoid closure issues
function startFadeOut(notification) {
    if (notification.dataset.fadingOut) return; // Already fading out
    
    // Set flag on element itself
    notification.dataset.fadingOut = 'true';
    
    // Remove ALL event listeners to prevent interference
    if (notification._hoverInHandler) {
        notification.removeEventListener('mouseenter', notification._hoverInHandler);
    }
    if (notification._hoverOutHandler) {
        notification.removeEventListener('mouseleave', notification._hoverOutHandler);
    }
    
    // Add fading-out class to disable interactions and stop animations
    notification.classList.add('fading-out');
    
    // Reset to neutral state immediately
    notification.style.transform = 'translateX(0)';
    notification.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    notification.style.animation = 'none';
    
    // Use requestAnimationFrame for smoother transitions
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            // Start opacity fade
            notification.classList.add('fading-out-opacity');
            
            // After opacity fade, start slide
            setTimeout(() => {
                notification.classList.add('fading-out-slide');
                
                // Remove after slide completes
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 400);
            }, 600);
        });
    });
}

console.log('Global PushNotifications system loaded');