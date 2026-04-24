/**
 * Notification System
 * Handles notification bell, dropdown, and toast notifications
 */

class NotificationManager {
    constructor() {
        this.unreadCount = 0;
        this.notifications = [];
        this.pollInterval = null;
        this.deleteMode = false;
        this.selectedNotifications = new Set();
        this.init();
    }

    init() {
        // Load initial notifications
        this.loadNotifications();
        
        // Poll for new notifications every 30 seconds
        this.pollInterval = setInterval(() => {
            this.checkForNewNotifications();
        }, 30000);

        // Setup event listeners
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Bell icon click - handle all notification bells
        const bellIcons = document.querySelectorAll('.notification-bell-trigger');
        console.log('Found notification bells:', bellIcons.length);
        
        bellIcons.forEach(bellIcon => {
            console.log('Setting up listener for:', bellIcon.id);
            bellIcon.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Bell clicked:', bellIcon.id);
                
                // Get the associated dropdown
                const dropdownId = bellIcon.id.replace('Bell', 'Dropdown');
                const dropdown = document.getElementById(dropdownId);
                
                console.log('Looking for dropdown:', dropdownId, 'Found:', dropdown);
                
                // Close all other dropdowns
                document.querySelectorAll('.notification-dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                        d.style.display = 'none';
                        // Exit delete mode when closing
                        if (d.classList.contains('delete-mode')) {
                            this.exitDeleteMode(d);
                        }
                    }
                });
                
                // Toggle this dropdown
                if (dropdown) {
                    const isShown = dropdown.classList.contains('show');
                    console.log('Dropdown current state - isShown:', isShown);
                    
                    if (isShown) {
                        dropdown.classList.remove('show');
                        dropdown.style.display = 'none';
                        // Exit delete mode when closing
                        if (dropdown.classList.contains('delete-mode')) {
                            this.exitDeleteMode(dropdown);
                        }
                        console.log('Hiding dropdown');
                    } else {
                        dropdown.classList.add('show');
                        dropdown.style.display = 'block';
                        dropdown.style.zIndex = '99999';
                        console.log('Showing dropdown, display:', dropdown.style.display, 'classList:', dropdown.classList);
                    }
                } else {
                    console.error('Dropdown not found!');
                }
            });
        });

        // Mark all as read - handle all buttons
        const markAllBtns = document.querySelectorAll('.mark-all-read');
        markAllBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.markAllAsRead();
            });
        });

        // Delete all notifications - handle all buttons (now enters delete mode)
        const deleteAllBtns = document.querySelectorAll('.delete-all-notifications');
        deleteAllBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = btn.closest('.notification-dropdown');
                this.enterDeleteMode(dropdown);
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-bell-trigger') && !e.target.closest('.notification-dropdown')) {
                document.querySelectorAll('.notification-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('show');
                    dropdown.style.display = 'none';
                    // Exit delete mode when closing
                    if (dropdown.classList.contains('delete-mode')) {
                        this.exitDeleteMode(dropdown);
                    }
                });
            }
        });
    }

    enterDeleteMode(dropdown) {
        this.deleteMode = true;
        this.selectedNotifications.clear();
        dropdown.classList.add('delete-mode');
        
        // Update header to show select all checkbox and action buttons
        const header = dropdown.querySelector('.notification-header');
        const titleElement = header.querySelector('h6');
        const actionsDiv = header.querySelector('.d-flex.gap-2');
        
        // Add select all checkbox next to title
        titleElement.innerHTML = `
            <input type="checkbox" class="select-all-checkbox me-2" id="selectAllNotifications" />
            <i class="fas fa-bell me-2"></i><span class="notification-text">Notifications</span>
        `;
        
        // Hide normal buttons and show delete mode buttons with text
        actionsDiv.innerHTML = `
            <div class="delete-mode-actions">
                <button class="cancel-delete-btn" title="Cancel">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="confirm-delete-btn" title="Delete Selected">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        `;
        
        // Add event listeners to new buttons
        const cancelBtn = actionsDiv.querySelector('.cancel-delete-btn');
        const confirmBtn = actionsDiv.querySelector('.confirm-delete-btn');
        const selectAllCheckbox = titleElement.querySelector('.select-all-checkbox');
        
        // Select all checkbox handler
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('click', (e) => {
                e.stopPropagation();
                const isChecked = selectAllCheckbox.checked;
                
                // Update all notification checkboxes
                dropdown.querySelectorAll('.notification-checkbox').forEach(cb => {
                    cb.checked = isChecked;
                    const notifId = parseInt(cb.closest('.notification-item').dataset.id);
                    if (isChecked) {
                        this.selectedNotifications.add(notifId);
                    } else {
                        this.selectedNotifications.delete(notifId);
                    }
                });
            });
        }
        
        cancelBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.exitDeleteMode(dropdown);
        });
        
        confirmBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.deleteSelectedNotifications(dropdown);
        });
    }

    exitDeleteMode(dropdown) {
        this.deleteMode = false;
        this.selectedNotifications.clear();
        
        // Wait for animation before removing class
        setTimeout(() => {
            dropdown.classList.remove('delete-mode');
        }, 100);
        
        // Restore normal header
        const header = dropdown.querySelector('.notification-header');
        const titleElement = header.querySelector('h6');
        const actionsDiv = header.querySelector('.d-flex.gap-2');
        
        // Restore title without checkbox
        titleElement.innerHTML = `<i class="fas fa-bell me-2"></i><span class="notification-text">Notifications</span>`;
        
        actionsDiv.innerHTML = `
            <a href="#" class="mark-all-read" title="Mark all as read">
                <i class="fas fa-check-double"></i>
            </a>
            <a href="#" class="delete-all-notifications" title="Delete all">
                <i class="fas fa-trash"></i>
            </a>
        `;
        
        // Re-attach event listeners
        const markAllBtn = actionsDiv.querySelector('.mark-all-read');
        const deleteAllBtn = actionsDiv.querySelector('.delete-all-notifications');
        
        markAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.markAllAsRead();
        });
        
        deleteAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.enterDeleteMode(dropdown);
        });
        
        // Uncheck all checkboxes
        dropdown.querySelectorAll('.notification-checkbox').forEach(cb => {
            cb.checked = false;
        });
    }

    async deleteSelectedNotifications(dropdown) {
        if (this.selectedNotifications.size === 0) {
            alert('Please select at least one notification to delete.');
            return;
        }
        
        if (!confirm(`Delete ${this.selectedNotifications.size} notification(s)?`)) {
            return;
        }
        
        try {
            const deletePromises = Array.from(this.selectedNotifications).map(id => 
                fetch(`/notifications/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
            );
            
            await Promise.all(deletePromises);
            
            // Remove deleted notifications from local state
            this.notifications = this.notifications.filter(n => !this.selectedNotifications.has(n.id));
            
            // Exit delete mode and refresh UI
            this.exitDeleteMode(dropdown);
            this.updateUI();
            this.updateUnreadCount();
        } catch (error) {
            console.error('Error deleting notifications:', error);
            alert('Failed to delete some notifications. Please try again.');
        }
    }

    async loadNotifications() {
        try {
            const response = await fetch('/notifications', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.notifications = data.data || [];
                this.updateUI();
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }

        // Also update unread count
        this.updateUnreadCount();
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/notifications/unread-count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.unreadCount = data.count || 0;
                this.updateBadge();
            }
        } catch (error) {
            console.error('Error updating unread count:', error);
        }
    }

    async checkForNewNotifications() {
        const oldCount = this.unreadCount;
        await this.updateUnreadCount();
        
        // If there are new notifications, reload the list
        if (this.unreadCount > oldCount) {
            await this.loadNotifications();
        }
    }

    updateBadge() {
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => {
            if (this.unreadCount > 0) {
                badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    updateUI() {
        const listContainers = document.querySelectorAll('.notification-list');
        if (listContainers.length === 0) return;

        const html = this.notifications.length === 0 
            ? `<div class="no-notifications">
                <i class="fas fa-seedling"></i>
                <p>No notifications yet</p>
            </div>`
            : this.notifications.map(notif => this.renderNotification(notif)).join('');

        listContainers.forEach(container => {
            container.innerHTML = html;
            
            // Add click handlers to notification items in this container
            container.querySelectorAll('.notification-item').forEach(item => {
                const checkbox = item.querySelector('.notification-checkbox');
                
                // Handle checkbox clicks in delete mode
                if (checkbox) {
                    checkbox.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const id = parseInt(item.dataset.id);
                        if (checkbox.checked) {
                            this.selectedNotifications.add(id);
                        } else {
                            this.selectedNotifications.delete(id);
                        }
                    });
                }
                
                // Handle item clicks (only when not in delete mode)
                item.addEventListener('click', (e) => {
                    // Don't navigate if clicking checkbox
                    if (e.target.classList.contains('notification-checkbox')) {
                        return;
                    }
                    
                    // Don't navigate in delete mode
                    if (this.deleteMode) {
                        // Toggle checkbox instead
                        if (checkbox) {
                            checkbox.checked = !checkbox.checked;
                            const id = parseInt(item.dataset.id);
                            if (checkbox.checked) {
                                this.selectedNotifications.add(id);
                            } else {
                                this.selectedNotifications.delete(id);
                            }
                        }
                        return;
                    }
                    
                    const id = item.dataset.id;
                    this.markAsRead(id);
                    
                    // Navigate to link if exists
                    const link = item.dataset.link;
                    if (link) {
                        window.location.href = link;
                    }
                });
            });
        });
    }

    renderNotification(notif) {
        const iconClass = this.getIconClass(notif.type);
        const icon = this.getIcon(notif.type);
        const timeAgo = this.getTimeAgo(notif.created_at);
        const unreadClass = notif.is_read ? '' : 'unread';

        return `
            <div class="notification-item ${unreadClass}" data-id="${notif.id}" data-link="${notif.link || ''}">
                <input type="checkbox" class="notification-checkbox" />
                <div class="notification-icon ${iconClass}">
                    <i class="${icon}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${this.escapeHtml(notif.title)}</div>
                    <div class="notification-message">${this.escapeHtml(notif.message)}</div>
                    <div class="notification-time">${timeAgo}</div>
                </div>
            </div>
        `;
    }

    getIconClass(type) {
        const iconMap = {
            'request_submitted': 'info',
            'request_approved': 'success',
            'request_sent': 'success',
            'client_approved': 'success',
            'client_rejected': 'warning',
            'new_request': 'info',
            'new_role_request': 'info'
        };
        return iconMap[type] || 'info';
    }

    getIcon(type) {
        const iconMap = {
            'request_submitted': 'fas fa-paper-plane',
            'request_approved': 'fas fa-check-circle',
            'request_sent': 'fas fa-truck',
            'client_approved': 'fas fa-user-check',
            'client_rejected': 'fas fa-times-circle',
            'new_request': 'fas fa-bell',
            'new_role_request': 'fas fa-user-plus'
        };
        return iconMap[type] || 'fas fa-bell';
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);

        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
        if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
        
        return date.toLocaleDateString();
    }

    async markAsRead(id) {
        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Update local state
                const notif = this.notifications.find(n => n.id == id);
                if (notif) {
                    notif.is_read = true;
                }
                this.updateUnreadCount();
                this.updateUI();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Update local state
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
                this.updateBadge();
                this.updateUI();
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }

    async deleteAllNotifications() {
        if (!confirm('Are you sure you want to delete all notifications? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch('/notifications/delete-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Clear local state
                this.notifications = [];
                this.unreadCount = 0;
                this.updateBadge();
                this.updateUI();
            }
        } catch (error) {
            console.error('Error deleting all notifications:', error);
        }
    }

    showToast(notification) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const iconClass = this.getIconClass(notification.type);
        const icon = this.getIcon(notification.type);

        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="notification-icon ${iconClass}">
                <i class="${icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${this.escapeHtml(notification.title)}</div>
                <div class="notification-message">${this.escapeHtml(notification.message)}</div>
            </div>
        `;

        toast.addEventListener('click', () => {
            if (notification.link) {
                window.location.href = notification.link;
            }
            this.hideToast(toast);
        });

        container.appendChild(toast);

        // Auto hide after 5 seconds
        setTimeout(() => {
            this.hideToast(toast);
        }, 5000);
    }

    hideToast(toast) {
        toast.classList.add('hiding');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    destroy() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
    }
}

// Initialize notification manager when DOM is ready
let notificationManager;
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== NOTIFICATION SYSTEM INITIALIZING ===');
    console.log('Looking for notification bells with class: .notification-bell-trigger');
    const bells = document.querySelectorAll('.notification-bell-trigger');
    console.log('Found bells:', bells);
    bells.forEach((bell, index) => {
        console.log(`Bell ${index}:`, bell.id, bell);
    });
    
    notificationManager = new NotificationManager();
    console.log('NotificationManager created:', notificationManager);
});
