

<?php $__env->startSection('title', 'Dashboard - Salenga Farm'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Match admin dashboard styling */
    .dashboard-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }
    
    .stat-card {
        background: white;
        border: 1px solid #e9ecef;
    }
    
    .stat-card.border-success {
        border-left: 4px solid #28a745 !important;
    }
    
    .stat-card.border-info {
        border-left: 4px solid #17a2b8 !important;
    }
    
    .stat-card.border-primary {
        border-left: 4px solid #007bff !important;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d5530;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        margin-top: 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    .stat-detail {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-item {
        padding: 0.875rem;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.2s;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background-color: #f8f9fa;
    }
    
    .activity-list-container {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .activity-list-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .activity-list-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .activity-list-container::-webkit-scrollbar-thumb {
        background: #28a745;
        border-radius: 10px;
    }
    
    .activity-list-container::-webkit-scrollbar-thumb:hover {
        background: #218838;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .activity-icon.success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .activity-icon.warning {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .activity-icon.info {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .activity-icon.danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .quick-action-btn {
        padding: 0.625rem 0.5rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background: white;
        transition: all 0.2s;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: inherit;
        text-align: center;
        height: 100%;
        min-height: 95px;
    }
    
    .quick-action-btn:hover {
        border-color: #28a745;
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: inherit;
    }
    
    .quick-action-icon {
        font-size: 1.5rem;
        color: #28a745;
        margin-bottom: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
    }
    
    .quick-action-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #2d5530;
        line-height: 1.2;
    }
    
    .pending-alert {
        border-left: 4px solid #ffc107;
        background-color: #fff9e6;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    
    .section-header {
        font-size: 1rem;
        font-weight: 600;
        color: #2d5530;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .section-header i {
        color: #28a745;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
    }
    
    .card-header h5, .card-header h6 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d5530;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991px) {
        .container-fluid {
            padding: 1rem !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="padding: 1.5rem 2rem;">
    <!-- Welcome Section -->
    <div class="mb-3">
        <h2 class="mb-1" style="font-size: 1.25rem; font-weight: 600;">
            <i class="fas fa-gauge me-2 text-success"></i>
            Welcome back, <?php echo e($user->first_name); ?>! 👋
        </h2>
        <p class="text-muted mb-0">
            <small>Last login: <?php echo e(now()->format('M d, Y \a\t g:i A')); ?></small>
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="dashboard-card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3">
                            <i class="fas fa-seedling fa-2x text-success opacity-50"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="stat-label mb-1">PLANT REQUESTS</h6>
                            <div class="stat-number"><?php echo e($inquiriesCount); ?></div>
                            <div class="stat-detail">
                                <?php echo e($inquiriesResponded); ?> responded, <?php echo e($inquiriesPending); ?> pending
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(route('my-requests.index')); ?>" class="btn btn-sm btn-outline-success mt-3 w-100">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3">
                            <i class="fas fa-calendar-check fa-2x text-success opacity-50"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="stat-label mb-1">SITE VISIT REQUESTS</h6>
                            <div class="stat-number"><?php echo e($siteVisitRequestsCount); ?></div>
                            <div class="stat-detail">
                                <?php echo e($siteVisitRequestsApproved); ?> approved, <?php echo e($siteVisitRequestsPending); ?> pending
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(route('my-requests.index')); ?>" class="btn btn-sm btn-outline-success mt-3 w-100">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card stat-card border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon me-3">
                            <i class="fas fa-folder-open fa-2x text-success opacity-50"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="stat-label mb-1">SITE DATA</h6>
                            <div class="stat-number"><?php echo e($siteVisitsCount); ?></div>
                            <div class="stat-detail">
                                <?php echo e($siteVisitsActive); ?> active, <?php echo e($siteVisitsCompleted); ?> completed
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(route('client-data.index')); ?>" class="btn btn-sm btn-outline-success mt-3 w-100">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Three-Column Layout (matching admin dashboard) -->
    <div class="row g-3">
        <!-- Left Column (25%) -->
        <div class="col-lg-3">
            <!-- Pending Actions -->
            <?php if($pendingActions->count() > 0): ?>
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-exclamation-circle me-2"></i>Pending Actions</h6>
                </div>
                <div class="card-body" style="padding: 0.75rem;">
                    <?php $__currentLoopData = $pendingActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pending-alert" style="margin-bottom: 0.75rem;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-<?php echo e($action['icon']); ?> me-2 mt-1" style="font-size: 1.25rem; color: #856404;"></i>
                            <div class="flex-grow-1">
                                <strong style="font-size: 0.875rem;"><?php echo e($action['title']); ?></strong>
                                <p class="mb-1 mt-1" style="font-size: 0.8rem;"><?php echo e($action['description']); ?></p>
                                <small class="text-muted" style="font-size: 0.75rem;"><?php echo e($action['date']); ?></small>
                                <a href="<?php echo e($action['link']); ?>" class="btn btn-sm btn-warning mt-2 w-100" style="font-size: 0.8rem;">
                                    <?php echo e($action['action']); ?> <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tips & Resources -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h6><i class="fas fa-lightbulb me-2 text-warning"></i>Tips & Resources</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small" style="font-size: 0.85rem; padding-left: 1.25rem;">
                        <li class="mb-2">Browse our <a href="<?php echo e(route('public.plants')); ?>">plant catalog</a> to submit inquiries</li>
                        <li class="mb-2">Request a site visit to get professional landscaping advice</li>
                        <li class="mb-2">Check <a href="<?php echo e(route('plant-care.index')); ?>">Plant Guide</a> for care instructions</li>
                        <li class="mb-0">We'll email you when your requests are processed</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Middle Column (50%) -->
        <div class="col-lg-6">
            <!-- Recent Activity -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h6><i class="fas fa-clock-rotate-left me-2"></i>Recent Activity</h6>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="activity-list-container">
                        <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="activity-item">
                            <div class="d-flex align-items-start">
                                <div class="activity-icon <?php echo e($activity['type']); ?> me-3">
                                    <i class="fas fa-<?php echo e($activity['icon']); ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong style="font-size: 0.9rem;"><?php echo e($activity['title']); ?></strong>
                                    <p class="mb-1 mt-1 text-muted small" style="font-size: 0.8rem;"><?php echo e($activity['description']); ?></p>
                                    <small class="text-muted" style="font-size: 0.75rem;"><?php echo e($activity['date']); ?></small>
                                </div>
                                <?php if(isset($activity['link'])): ?>
                                <a href="<?php echo e($activity['link']); ?>" class="btn btn-sm btn-outline-success" style="font-size: 0.8rem;">
                                    View <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p class="mb-0">No recent activity</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (25%) -->
        <div class="col-lg-3">
            <!-- Quick Actions -->
            <div class="dashboard-card mb-3">
                <div class="card-header">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?php echo e(route('public.plants')); ?>" class="quick-action-btn">
                                <div class="quick-action-icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                                <div class="quick-action-text">Submit Plant Inquiry</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('site-visit-requests.create')); ?>" class="quick-action-btn">
                                <div class="quick-action-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="quick-action-text">Request Site Visit</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('my-requests.index')); ?>" class="quick-action-btn">
                                <div class="quick-action-icon">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="quick-action-text">View My Requests</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo e(route('client-data.index')); ?>" class="quick-action-btn">
                                <div class="quick-action-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div class="quick-action-text">View Site Data</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/dashboard/overview.blade.php ENDPATH**/ ?>