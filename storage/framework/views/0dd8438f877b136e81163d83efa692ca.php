<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Site Visit Requests - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/responsive-fixes.css')); ?>" rel="stylesheet">
    <style>
        .dashboard-flex .main-content {
            padding-left: 15px !important;
            padding-right: 15px !important;
            padding-top: 15px !important;
        }
        .requests-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .requests-table table {
            margin-bottom: 0;
        }
        .request-row {
            transition: background-color 0.2s ease;
            border-left: 4px solid transparent;
        }
        .request-row:hover {
            background-color: #f8f9fa;
        }
        .request-row.pending {
            border-left-color: #ffc107;
        }
        .request-row.approved {
            border-left-color: #28a745;
        }
        .request-row.rejected {
            border-left-color: #dc3545;
        }
        .expand-btn {
            transition: transform 0.3s ease;
            text-decoration: none !important;
        }
        .expand-btn.expanded {
            transform: rotate(90deg);
        }
        .details-row {
            border-left: 4px solid #e9ecef;
        }
        .details-content {
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .photo-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            border: 2px solid #dee2e6;
        }
        .photo-thumbnail:hover {
            opacity: 0.8;
            border-color: #28a745;
        }
        .empty-state {
            background: white;
            border-radius: 8px;
            padding: 3rem;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        
        <div class="main-content">
            <div style="padding-top: 0;">
                <div class="p-0">
                    <!-- Header -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mb-0" style="color: #2d5530; font-weight: 600;">
                                    <i class="fas fa-inbox me-2"></i>Site Visit Requests
                                </h2>
                                <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Site Visits
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if(session('success')): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                if (window.PushNotifications) {
                                    window.PushNotifications.show('success', '<?php echo e(session('success')); ?>', true);
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                if (window.PushNotifications) {
                                    window.PushNotifications.show('danger', '<?php echo e(session('error')); ?>', false);
                                }
                            });
                        </script>
                    <?php endif; ?>

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" 
                                    data-bs-target="#pending" type="button" role="tab">
                                Pending 
                                <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingRequests->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" 
                                    data-bs-target="#approved" type="button" role="tab">
                                Approved 
                                <span class="badge bg-success ms-1"><?php echo e($approvedRequests->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" 
                                    data-bs-target="#rejected" type="button" role="tab">
                                Rejected 
                                <span class="badge bg-danger ms-1"><?php echo e($rejectedRequests->count()); ?></span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Pending Requests -->
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <?php if($pendingRequests->count() > 0): ?>
                                <div class="requests-table">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Client Name</th>
                                                <th>Email</th>
                                                <th>Preferred Date</th>
                                                <th>Property Address</th>
                                                <th class="text-center" style="width: 100px;">Status</th>
                                                <th class="text-center" style="width: 150px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo $__env->make('site-visit-requests.partials.request-card', ['request' => $request], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">No Pending Requests</h5>
                                    <p class="text-muted">All site visit requests have been reviewed.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Approved Requests -->
                        <div class="tab-pane fade" id="approved" role="tabpanel">
                            <?php if($approvedRequests->count() > 0): ?>
                                <div class="requests-table">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Client Name</th>
                                                <th>Email</th>
                                                <th>Preferred Date</th>
                                                <th>Property Address</th>
                                                <th class="text-center" style="width: 100px;">Status</th>
                                                <th class="text-center" style="width: 150px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $approvedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo $__env->make('site-visit-requests.partials.request-card', ['request' => $request], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-check-circle text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">No Approved Requests</h5>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Rejected Requests -->
                        <div class="tab-pane fade" id="rejected" role="tabpanel">
                            <?php if($rejectedRequests->count() > 0): ?>
                                <div class="requests-table">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Client Name</th>
                                                <th>Email</th>
                                                <th>Preferred Date</th>
                                                <th>Property Address</th>
                                                <th class="text-center" style="width: 100px;">Status</th>
                                                <th class="text-center" style="width: 150px;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $rejectedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo $__env->make('site-visit-requests.partials.request-card', ['request' => $request], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-times-circle text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 text-muted">No Rejected Requests</h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Site Visit Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="approveForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-body">
                        <p>Are you sure you want to approve this site visit request?</p>
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Add any notes for the client..."></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            After approval, you can manually create the actual site visit from the Site Visits page.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Approve Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Site Visit Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-body">
                        <p>Please provide a reason for rejecting this request:</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                      placeholder="Explain why this request cannot be accommodated..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-2"></i>Reject Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Property Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalPhoto" src="" alt="Property Photo" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete Site Visit Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone.
                        </div>
                        <p>Are you sure you want to delete this site visit request?</p>
                        <p class="text-muted mb-0">All associated photos and data will be permanently removed.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/push-notifications-global.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/push-notifications.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <script>
    // Toggle expandable details
    function toggleDetails(requestId) {
        const detailsRow = document.getElementById('details-' + requestId);
        const icon = document.getElementById('icon-' + requestId);
        const button = icon.closest('.expand-btn');
        
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
            button.classList.add('expanded');
        } else {
            detailsRow.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
            button.classList.remove('expanded');
        }
    }

    // Approve request
    function approveRequest(requestId) {
        const form = document.getElementById('approveForm');
        form.action = `/site-visit-requests/${requestId}/approve`;
        const modal = new bootstrap.Modal(document.getElementById('approveModal'));
        modal.show();
    }

    // Reject request
    function rejectRequest(requestId) {
        const form = document.getElementById('rejectForm');
        form.action = `/site-visit-requests/${requestId}/reject`;
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }

    // Delete request
    function deleteRequest(requestId) {
        const form = document.getElementById('deleteForm');
        form.action = `/site-visit-requests/${requestId}`;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // View photo
    function viewPhoto(photoUrl) {
        document.getElementById('modalPhoto').src = photoUrl;
        const modal = new bootstrap.Modal(document.getElementById('photoModal'));
        modal.show();
    }

    // Sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebarToggle && sidebar && overlay) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
        }
    });
    </script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visit-requests/index.blade.php ENDPATH**/ ?>