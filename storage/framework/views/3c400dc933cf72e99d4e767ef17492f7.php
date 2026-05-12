<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Users - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>?v=4" rel="stylesheet">
    <link href="<?php echo e(asset('css/inventory.css')); ?>?v=2" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
</head>
<body class="bg-light dashboard-page">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div style="padding-top: 0; padding-bottom: 0;">
                <div class="p-0" style="margin-bottom: 0;">
                    <!-- Title and Add Account Button -->
                    <div class="d-flex justify-content-between align-items-center" style="margin-top: 15px; margin-bottom: 15px;">
                        <h2 class="mb-0">User Management</h2>
                        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i> Add Account
                        </a>
                    </div>
                    
                    <!-- Notification Container -->
                    <div class="notification-container">
                        <?php if(session('success') || session('error')): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                <?php if(session('success')): ?>
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('success', '<?php echo e(session('success')); ?>', true);
                                    }
                                <?php endif; ?>
                                
                                <?php if(session('error')): ?>
                                    if (window.PushNotifications) {
                                        window.PushNotifications.show('danger', '<?php echo e(session('error')); ?>', false);
                                    }
                                <?php endif; ?>
                            });
                        </script>
                        <?php endif; ?>
                    </div>
                    
                    <!-- User Accounts Section -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No.</th>
                                            <th style="width: 150px;">First Name</th>
                                            <th style="width: 150px;">Last Name</th>
                                            <th style="width: 220px;">Email</th>
                                            <th style="width: 100px;">Role</th>
                                            <th class="text-end" style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="text-align: left;"><?php echo e($index + 1); ?></td>
                                            <td style="text-align: left;"><?php echo e($user->first_name); ?></td>
                                            <td style="text-align: left;"><?php echo e($user->last_name); ?></td>
                                            <td style="text-align: left;"><?php echo e($user->email); ?></td>
                                            <td style="text-align: left;">
                                                <?php
                                                    $badgeColor = match($user->role) {
                                                        'super_admin' => 'bg-warning text-dark',
                                                        'admin' => 'bg-danger',
                                                        'client' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                ?>
                                                <span class="badge <?php echo e($badgeColor); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-link text-success p-0 me-2" title="Edit">
                                                    <i class="fas fa-edit" style="font-size: 1.2rem;"></i>
                                                </a>
                                                <button type="button" class="btn btn-link text-danger p-0 delete-user-btn" 
                                                        data-user-id="<?php echo e($user->id); ?>" 
                                                        data-user-name="<?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>"
                                                        title="Delete">
                                                    <i class="fas fa-trash" style="font-size: 1.2rem;"></i>
                                                </button>
                                                <form id="delete-user-form-<?php echo e($user->id); ?>" action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="d-none">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete User Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Are you sure you want to delete this user?</h5>
                    <p class="text-muted mb-2"><strong id="deleteUserName"></strong></p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUser">
                        <i class="fas fa-trash me-1"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Role Request Confirmation Modal -->
    <div class="modal fade" id="approveRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve Client Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #198754;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Approve this client request?</h5>
                    <p class="text-muted mb-2"><strong id="approveRequestName"></strong></p>
                    <p class="text-success"><small>This user will be granted client access and receive a notification.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="approveRoleRequestForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Yes, Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Role Request Confirmation Modal -->
    <div class="modal fade" id="rejectRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Client Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #dc3545;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Reject this client request?</h5>
                    <p class="text-muted mb-2"><strong id="rejectRequestName"></strong></p>
                    <p class="text-danger"><small>The user will be notified that their request was reviewed.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="rejectRoleRequestForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> Yes, Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Done Role Request Confirmation Modal -->
    <div class="modal fade" id="doneRoleRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Remove Role Request</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="font-size: 4rem; color: #0d6efd;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 class="mt-3 mb-3">Remove this role request from the list?</h5>
                    <p class="text-muted mb-2"><strong id="doneRequestName"></strong></p>
                    <p class="text-muted"><small>This request has been processed and will be removed from the list.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form id="doneRoleRequestForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> Yes, Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/push-notifications-global.js')); ?>?v=fadefix<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/push-notifications.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <script>
        // Check if we should activate the role requests tab
        <?php if(session('activeTab') === 'role-requests'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const roleRequestsTab = document.getElementById('role-requests-tab');
            const roleRequestsPane = document.getElementById('role-requests');
            const accountsTab = document.getElementById('accounts-tab');
            const accountsPane = document.getElementById('accounts');
            
            // Deactivate accounts tab
            accountsTab.classList.remove('active');
            accountsPane.classList.remove('show', 'active');
            
            // Activate role requests tab
            roleRequestsTab.classList.add('active');
            roleRequestsPane.classList.add('show', 'active');
        });
        <?php endif; ?>
        
        // Delete User Modal
        let userIdToDelete = null;
        
        $('.delete-user-btn').on('click', function() {
            userIdToDelete = $(this).data('user-id');
            const userName = $(this).data('user-name');
            $('#deleteUserName').text(userName);
            $('#deleteUserModal').modal('show');
        });
        
        $('#confirmDeleteUser').on('click', function() {
            if (userIdToDelete) {
                $('#delete-user-form-' + userIdToDelete).submit();
            }
        });
        
        // Approve Role Request Modal
        $('.approve-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#approveRequestName').text(requestName);
            $('#approveRoleRequestForm').attr('action', '/users/role-requests/' + requestId + '/approve');
            $('#approveRoleRequestModal').modal('show');
        });
        
        // Reject Role Request Modal
        $('.reject-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#rejectRequestName').text(requestName);
            $('#rejectRoleRequestForm').attr('action', '/users/role-requests/' + requestId + '/reject');
            $('#rejectRoleRequestModal').modal('show');
        });
        
        // Done Role Request Modal
        $('.done-role-request-btn').on('click', function() {
            const requestId = $(this).data('request-id');
            const requestName = $(this).data('request-name');
            $('#doneRequestName').text(requestName);
            $('#doneRoleRequestForm').attr('action', '/users/role-requests/' + requestId);
            $('#doneRoleRequestModal').modal('show');
        });
    </script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/admin/users/index.blade.php ENDPATH**/ ?>