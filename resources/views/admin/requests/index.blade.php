@extends('layouts.public')

@section('title', 'Requests - Salenga Farm')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3">Plant Requests</h1>
            <p class="text-muted">Manage plant requests from both clients and users</p>
        </div>
    </div>

    <!-- Notification Container with Push Animation -->
    <div class="notification-container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible push-notification" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close notification-close" aria-label="Close"></button>
            <div class="alert-countdown-bar"></div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible push-notification" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close notification-close" aria-label="Close"></button>
            <div class="alert-countdown-bar"></div>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible push-notification" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close notification-close" aria-label="Close"></button>
            <div class="alert-countdown-bar"></div>
        </div>
        @endif
    </div>

    <!-- Tabbed Navigation -->
    <ul class="nav nav-tabs nav-fill mb-4" id="requestTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-requests"
                    type="button" role="tab" aria-controls="client-requests" aria-selected="true">
                <i class="fas fa-building me-2"></i>Client Requests
                <span class="badge bg-primary ms-2">{{ count($clientRequests) }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-requests"
                    type="button" role="tab" aria-controls="user-requests" aria-selected="false">
                <i class="fas fa-users me-2"></i>User Requests
                <span class="badge bg-primary ms-2">{{ count($userRequests) }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="requestTabContent">
        <!-- Client Requests Tab -->
        <div class="tab-pane fade show active" id="client-requests" role="tabpanel" aria-labelledby="client-tab">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive requests-table-container">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Request Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Pricing Options</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientRequests as $request)
                                    <tr class="align-middle">
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->name }}</td>
                                        <td>{{ $request->email }}</td>
                                        <td>{{ $request->request_date->format('M d, Y') }}</td>
                                        <td>{{ $request->due_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status == 'sent' || $request->status == 'responded')
                                                <span class="badge bg-success">Responded</span>
                                            @elseif($request->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ count($request->items_json) }} plants</td>
                                        <td>
                                            @php
                                                $pricing = $request->pricing ?? 'None';
                                                $pricingClass = '';
                                                if($pricing == 'Low cost') {
                                                    $pricingClass = 'text-success';
                                                } elseif($pricing == 'High cost') {
                                                    $pricingClass = 'text-danger';
                                                }
                                            @endphp
                                            <span class="{{ $pricingClass }}">{{ $pricing }}</span>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('requests.view', $request->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View request details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role !== 'super_admin')
                                                @if($request->status == 'pending')
                                                <form action="{{ route('requests.send-email', $request->id) }}" method="POST" style="display:inline-block;" class="email-form" data-recipient-name="{{ $request->name }}" data-recipient-email="{{ $request->email }}" data-recipient-type="Client">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success email-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Email to Client">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                </form>
                                                @elseif(($request->status == 'sent' || $request->status == 'responded') && $request->pdf_path)
                                                <a href="{{ route('requests.download-pdf', $request->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @endif
                                                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-request-btn" data-request-id="{{ $request->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete this request">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">No client requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Requests Tab -->
        <div class="tab-pane fade" id="user-requests" role="tabpanel" aria-labelledby="user-tab">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive requests-table-container">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userRequests as $request)
                                    <tr class="align-middle">
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->name }}</td>
                                        <td>{{ $request->email }}</td>
                                        <td>{{ $request->phone }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}</td>
                                        <td>
                                            @if($request->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status == 'sent' || $request->status == 'responded')
                                                <span class="badge bg-success">Responded</span>
                                            @elseif($request->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ is_array($request->items_json) ? count($request->items_json) : 0 }} plants</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('requests.view', $request->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View request details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->role !== 'super_admin')
                                                @if($request->status == 'pending')
                                                <form action="{{ route('requests.send-email', $request->id) }}" method="POST" style="display:inline-block;" class="email-form" data-recipient-name="{{ $request->name }}" data-recipient-email="{{ $request->email }}" data-recipient-type="User">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success email-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Send Email to User">
                                                        <i class="fas fa-envelope"></i>
                                                    </button>
                                                </form>
                                                @elseif(($request->status == 'sent' || $request->status == 'responded') && $request->pdf_path)
                                                <a href="{{ route('requests.download-pdf', $request->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Download PDF">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @endif
                                                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-request-btn" data-request-id="{{ $request->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete this request">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No user requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Email Sending Loading Modal -->
<div class="modal fade" id="emailLoadingModal" tabindex="-1" aria-labelledby="emailLoadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-3">Sending Email...</h5>
                <p class="text-muted mb-3">Please wait while we send the email to <span id="emailRecipient"></span></p>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="emailProgress"></div>
                </div>
                <button type="button" class="btn btn-outline-danger" id="cancelEmailBtn">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this request? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
<script>
    $(document).ready(function() {
        // Check if we should activate a specific tab
        @if(session('activeTab'))
        const activeTab = '{{ session("activeTab") }}';
        if (activeTab === 'user-requests') {
            $('#user-tab').tab('show');
        } else if (activeTab === 'client-requests') {
            $('#client-tab').tab('show');
        }
        @endif
        
        // Auto-dismiss alerts after 5 seconds with countdown animation
        const alerts = document.querySelectorAll('.alert.push-notification');
        alerts.forEach(function(alert) {
            const countdownBar = alert.querySelector('.alert-countdown-bar');
            
            // Start countdown animation
            if (countdownBar) {
                countdownBar.style.animation = 'countdown 5s linear forwards';
            }
            
            // Auto-dismiss after 5 seconds
            const dismissTimer = setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
            
            // Manual close button - clear timer if manually closed
            const closeBtn = alert.querySelector('.notification-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    clearTimeout(dismissTimer);
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }
        });
        
        // Check if a specific tab should be shown based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (tabParam === 'user') {
            $('#user-tab').tab('show');
        }

        // Enable swipe functionality between tabs
        const container = document.querySelector('.container-fluid');
        let touchStartX = 0;
        let touchEndX = 0;

        container.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, false);

        container.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);

        function handleSwipe() {
            const swipeThreshold = 100; // minimum distance to trigger swipe

            if (touchEndX + swipeThreshold < touchStartX) {
                // Swipe left - go to user requests
                $('#user-tab').tab('show');
            } else if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - go to client requests
                $('#client-tab').tab('show');
            }
        }

        // Update URL when tab changes
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const targetId = $(e.target).attr('id');

            if (targetId === 'user-tab') {
                history.replaceState(null, null, '?tab=user');
            } else {
                history.replaceState(null, null, '?tab=client');
            }
        });

        // Handle delete request functionality
        let formToDelete = null;

        // Delete request button click
        $('.delete-request-btn').on('click', function() {
            formToDelete = $(this).closest('form');
            $('#deleteConfirmModal').modal('show');
        });

        // Confirm delete button click
        $('#confirmDelete').on('click', function() {
            if (formToDelete) {
                formToDelete.submit();
            }
            $('#deleteConfirmModal').modal('hide');
        });

        // Properly initialize and configure tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body,
                container: 'body',
                trigger: 'hover',
                animation: false,
                popperConfig: {
                    modifiers: [
                        {
                            name: 'preventOverflow',
                            options: {
                                altAxis: true,
                                boundary: document.body
                            }
                        }
                    ]
                }
            });
        });

        // Close tooltips when scrolling to prevent position issues
        $(window).on('scroll', function() {
            $('.tooltip').remove();
        });

        // Remove tooltip when mouse leaves
        $('[data-bs-toggle="tooltip"]').on('mouseleave', function() {
            var tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.hide();
            }
        });

        // Push Notification Animation System
        function initPushNotifications() {
            const notifications = document.querySelectorAll('.push-notification');
            const content = document.getElementById('requestTabs');
            
            notifications.forEach((notification, index) => {
                // Add content push class for smooth transitions
                if (content) {
                    content.classList.add('content-pushed');
                }
                
                // Animate notification in after a small delay
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100 + (index * 100)); // Stagger multiple notifications
                
                // Auto-hide success notifications after 5 seconds
                if (notification.classList.contains('alert-success')) {
                    setTimeout(() => {
                        hideNotification(notification);
                    }, 5000);
                }
                
                // Handle manual close button
                const closeBtn = notification.querySelector('.notification-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        hideNotification(notification);
                    });
                }
            });
        }
        
        function hideNotification(notification) {
            // Add hiding class for smooth exit animation
            notification.classList.add('hide');
            notification.classList.remove('show');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                notification.remove();
                
                // Check if any notifications remain
                const remainingNotifications = document.querySelectorAll('.push-notification');
                if (remainingNotifications.length === 0) {
                    // Remove content push class if no notifications remain
                    const content = document.getElementById('requestTabs');
                    if (content) {
                        content.classList.remove('content-pushed');
                    }
                }
            }, 500); // Match the CSS transition duration
        }
        
        // Initialize push notifications on page load
        initPushNotifications();
        
        // Email sending functionality with loading modal
        let currentEmailRequest = null;
        
        // Handle email sending with loading modal
        $('.email-btn').on('click', function(e) {
            e.preventDefault();
            
            console.log('Email button clicked');
            
            const form = $(this).closest('.email-form');
            const recipientName = form.data('recipient-name');
            const recipientEmail = form.data('recipient-email');
            const recipientType = form.data('recipient-type');
            
            console.log('Form data:', {
                recipientName,
                recipientEmail,
                recipientType,
                action: form.attr('action')
            });
            
            // Show loading modal
            $('#emailRecipient').text(`${recipientName} (${recipientEmail})`);
            $('#emailLoadingModal').modal('show');
            
            // Start progress animation
            startEmailProgress();
            
            // Create FormData for AJAX request
            const formData = new FormData(form[0]);
            
            console.log('Sending AJAX request to:', form.attr('action'));
            
            // Send AJAX request
            currentEmailRequest = $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    
                    // Complete progress
                    $('#emailProgress').css('width', '100%').removeClass('progress-bar-animated');
                    
                    // Hide modal after brief delay
                    setTimeout(() => {
                        $('#emailLoadingModal').modal('hide');
                        
                        // Show success notification using global system
                        if (window.PushNotifications) {
                            const message = `<i class="fas fa-check-circle me-2"></i>Email sent successfully to ${recipientName} (${recipientEmail})!`;
                            window.PushNotifications.show('success', message, true);
                        } else {
                            console.error('PushNotifications not available');
                        }
                        
                        // Refresh page after short delay to update status
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', {xhr, status, error, responseText: xhr.responseText});
                    
                    // Hide modal
                    $('#emailLoadingModal').modal('hide');
                    
                    // Only show error if it wasn't cancelled
                    if (status !== 'abort') {
                        let errorMessage = 'Failed to send email. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        if (window.PushNotifications) {
                            window.PushNotifications.show('danger', `<i class="fas fa-exclamation-circle me-2"></i>${errorMessage}`, false);
                        }
                    }
                },
                complete: function() {
                    currentEmailRequest = null;
                }
            });
        });
        
        // Cancel email sending
        $('#cancelEmailBtn').on('click', function() {
            if (currentEmailRequest) {
                currentEmailRequest.abort();
                currentEmailRequest = null;
            }
            $('#emailLoadingModal').modal('hide');
            
            if (window.PushNotifications) {
                window.PushNotifications.show('warning', '<i class="fas fa-info-circle me-2"></i>Email sending cancelled.', true);
            }
        });
        
        // Progress animation function
        function startEmailProgress() {
            $('#emailProgress').css('width', '0%').addClass('progress-bar-animated');
            
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) {
                    progress = 90;
                }
                $('#emailProgress').css('width', progress + '%');
                
                if (!currentEmailRequest || progress >= 90) {
                    clearInterval(progressInterval);
                }
            }, 200);
        }
        
        // Reset modal when hidden
        $('#emailLoadingModal').on('hidden.bs.modal', function() {
            $('#emailProgress').css('width', '0%').addClass('progress-bar-animated');
        });

        // Add subtle bounce effect to notifications when hovered
        $(document).on('mouseenter', '.push-notification', function() {
            $(this).css('transform', 'translateY(0) scale(1.02)');
        }).on('mouseleave', '.push-notification', function() {
            $(this).css('transform', 'translateY(0) scale(1)');
        });
    });
</script>
@endsection

@push('styles')
<link href="{{ asset('css/no-hover.css') }}?v={{ time() }}" rel="stylesheet">
<link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
<style>
/* Override global card height constraint from public.css */
#client-requests .card,
#user-requests .card {
    height: auto !important;
    min-height: calc(100vh - 200px) !important;
}

#client-requests .card-body,
#user-requests .card-body {
    height: auto !important;
    display: block !important;
    padding: 1rem !important;
}

/* Larger row height for tables */
.table td, .table th {
    padding: 0.75rem;
    vertical-align: middle;
}

/* Increase table container height - align with logout button at bottom */
.requests-table-container {
    min-height: calc(100vh - 250px) !important;
    max-height: calc(100vh - 250px) !important;
    overflow-y: auto !important;
    overflow-x: auto !important;
}

/* Swipe indicator styles */
.swipe-indicator {
    text-align: center;
    padding: 5px;
    color: #6c757d;
    font-size: 0.8rem;
    margin-top: -10px;
    margin-bottom: 10px;
}

/* Active tab indicator */
.nav-tabs .nav-link.active {
    font-weight: bold;
    border-bottom-width: 3px;
}

/* Smooth tab transitions */
.tab-pane {
    transition: all 0.3s ease;
}

/* Badge styling */
.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Push Notification Styles */
#notification-container {
    position: relative;
    z-index: 1000;
    overflow: hidden;
}

.push-notification {
    transform: translateY(-100%);
    opacity: 0;
    margin-bottom: 0;
    transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: none;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.push-notification.show {
    transform: translateY(0);
    opacity: 1;
    margin-bottom: 1rem;
}

.push-notification.hide {
    transform: translateY(-100%);
    opacity: 0;
    margin-bottom: 0;
    pointer-events: none;
}

/* Countdown bar animation */
.alert-countdown-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100%;
    background: rgba(0, 0, 0, 0.2);
    transform-origin: left;
}

@keyframes countdown {
    from {
        transform: scaleX(1);
    }
    to {
        transform: scaleX(0);
    }
}

/* Content push effect */
.content-pushed {
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.content-pushed.push-down {
    transform: translateY(0);
}

.content-pushed.push-up {
    transform: translateY(-60px);
}

/* Enhanced notification styling */
.push-notification .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.push-notification .btn-close:hover {
    opacity: 1;
    transform: scale(1.1);
}

/* Alert type specific styling */
.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}
</style>
@endpush