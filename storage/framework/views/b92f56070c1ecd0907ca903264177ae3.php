<?php
    use Illuminate\Support\Str;
?>



<?php $__env->startSection('title', 'My Requests - Salenga Farm'); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/loading.css')); ?>" rel="stylesheet">
<style>
    /* Copy all styles from user.blade.php */
    .container-fluid {
        height: auto !important;
        min-height: auto !important;
        overflow: visible !important;
    }
    
    /* Tab Styles */
    .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-radius: 8px 8px 0 0;
        color: #6c757d;
        font-weight: 600;
        padding: 12px 20px;
        margin-right: 4px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        background: #e9ecef;
        color: #495057;
    }
    
    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        color: white;
        border-color: transparent;
    }
    
    .tab-content {
        min-height: 400px;
    }
    
    .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        margin-left: 8px;
    }
    
    .nav-tabs .nav-link:not(.active) .tab-badge {
        background: #6c757d;
        color: white;
    }
    
    .custom-inquiries-table {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        background: white !important;
        margin: 0 !important;
        border: none !important;
        font-family: inherit !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
    }
    
    .custom-inquiries-table thead {
        background: linear-gradient(135deg, #198754 0%, #157347 100%) !important;
        border-bottom: none !important;
    }
    
    .custom-inquiries-table th {
        padding: 14px 16px !important;
        text-align: left !important;
        font-weight: 600 !important;
        color: white !important;
        border: none !important;
        background: transparent !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    .custom-inquiries-table td {
        padding: 14px 16px !important;
        border-bottom: 1px solid #e9ecef !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        vertical-align: middle !important;
        background: white !important;
        color: #495057 !important;
    }
    
    .custom-inquiries-table tbody tr {
        transition: all 0.2s ease !important;
    }
    
    .custom-inquiries-table tbody tr:hover {
        background-color: #f8f9fa !important;
        transform: translateX(2px) !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    }
    
    .custom-inquiries-table tbody tr:hover td {
        background-color: #f8f9fa !important;
    }
    
    .custom-inquiries-table tbody tr td:first-child {
        font-weight: 600 !important;
        color: #198754 !important;
    }
    
    .inquiries-table-container {
        background: white !important;
        border-radius: 8px !important;
        overflow: visible !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        height: auto !important;
        max-height: none !important;
    }
    
    .action-icon {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        padding: 0 !important;
        margin: 0 4px !important;
        border: none !important;
        border-radius: 50% !important;
        font-size: 14px !important;
        text-decoration: none !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    
    .action-icon-primary {
        background-color: #007bff !important;
        color: white !important;
    }
    
    .action-icon-primary:hover {
        background-color: #0056b3 !important;
        transform: scale(1.1) !important;
    }
    
    .action-icon-success {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .action-icon-success:hover {
        background-color: #1e7e34 !important;
        transform: scale(1.1) !important;
    }
    
    .action-icon-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }
    
    .action-icon-danger:hover {
        background-color: #c82333 !important;
        transform: scale(1.1) !important;
    }
    
    .status-badge {
        padding: 4px 8px !important;
        border-radius: 12px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        text-transform: uppercase !important;
        display: inline-block !important;
    }
    
    .status-badge.success {
        background-color: #d4edda !important;
        color: #155724 !important;
        border: 1px solid #c3e6cb !important;
    }
    
    .status-badge.warning {
        background-color: #fff3cd !important;
        color: #856404 !important;
        border: 1px solid #ffeaa7 !important;
    }
    
    .status-badge.danger {
        background-color: #f8d7da !important;
        color: #721c24 !important;
        border: 1px solid #f5c6cb !important;
    }
    
    .status-badge.secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
        border: 1px solid #d6d8db !important;
    }
    
    .table-scroll-wrapper {
        max-height: 420px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    
    .custom-inquiries-table thead {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
    }
    
    .inquiries-table-header {
        background: linear-gradient(135deg, #198754 0%, #157347 100%) !important;
        padding: 16px 20px !important;
        border-radius: 8px 8px 0 0 !important;
    }
    
    .inquiries-table-header h6 {
        margin: 0 !important;
        font-weight: 600 !important;
        color: white !important;
        font-size: 16px !important;
        text-transform: uppercase !important;
    }
    
    .no-requests {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .no-requests i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    /* Site Visit Request specific styles */
    .request-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }
    
    .request-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .request-date {
        font-weight: 600;
        color: #198754;
    }
    
    .request-property {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .request-status {
        margin-bottom: 0.5rem;
    }
    
    .admin-notes {
        background: #f8f9fa;
        border-left: 3px solid #198754;
        padding: 0.5rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
    
    .rejection-reason {
        background: #f8f9fa;
        border-left: 3px solid #dc3545;
        padding: 0.5rem;
        margin-top: 0.5rem;
        font-size: 0.9rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-list-check me-2 text-success"></i>My Requests</h4>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="plant-inquiries-tab" data-bs-toggle="tab" data-bs-target="#plant-inquiries" type="button" role="tab">
                        💬 Plant Inquiries
                        <span class="tab-badge"><?php echo e($plantInquiries->count()); ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rfq-requests-tab" data-bs-toggle="tab" data-bs-target="#rfq-requests" type="button" role="tab">
                        📄 RFQ Requests
                        <span class="tab-badge"><?php echo e($rfqRequests->count()); ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="site-visit-requests-tab" data-bs-toggle="tab" data-bs-target="#site-visit-requests" type="button" role="tab">
                        📋 Site Visit Requests
                        <span class="tab-badge"><?php echo e($siteVisitRequests->count()); ?></span>
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="requestTabContent">
                <!-- Tab 1: Plant Inquiries -->
                <div class="tab-pane fade show active" id="plant-inquiries" role="tabpanel">
                    <?php if($plantInquiries->count() > 0): ?>
                        <div class="inquiries-table-container">
                            <div class="inquiries-table-header">
                                <h6><i class="fas fa-seedling me-2"></i>Plant Inquiries</h6>
                            </div>
                            <div class="table-scroll-wrapper">
                                <table class="custom-inquiries-table">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Plant Details</th>
                                            <th>Message</th>
                                            <th>Date Submitted</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $plantInquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($inquiry->id); ?></td>
                                            <td>
                                                <?php if($inquiry->items_json && is_array($inquiry->items_json)): ?>
                                                    <?php $__currentLoopData = $inquiry->items_json; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="mb-1">
                                                            <strong><?php echo e($item['name'] ?? 'N/A'); ?></strong>
                                                            <?php if(isset($item['code']) && $item['code']): ?>
                                                                <small class="text-muted">(<?php echo e($item['code']); ?>)</small>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No plant details</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($inquiry->message): ?>
                                                    <?php echo e(Str::limit($inquiry->message, 50)); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">No message</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($inquiry->created_at->format('M d, Y')); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo e($inquiry->status === 'responded' ? 'success' : 'warning'); ?>">
                                                    <?php echo e(ucfirst($inquiry->status)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($inquiry->status === 'responded' && $inquiry->response_sent_at): ?>
                                                    <a href="<?php echo e(route('user.inquiry.response', $inquiry->id)); ?>" 
                                                       class="action-icon action-icon-primary" 
                                                       title="View Response">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" 
                                                        class="action-icon action-icon-danger" 
                                                        onclick="confirmDeleteInquiry(<?php echo e($inquiry->id); ?>)"
                                                        title="Delete Inquiry">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-inquiry-form-<?php echo e($inquiry->id); ?>" 
                                                      action="<?php echo e(route('user.plant-request.destroy', $inquiry->id)); ?>" 
                                                      method="POST" style="display: none;">
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
                    <?php else: ?>
                        <div class="no-requests">
                            <i class="fas fa-seedling"></i>
                            <h5>No Plant Inquiries</h5>
                            <p>You haven't submitted any plant inquiries yet.</p>
                            <a href="<?php echo e(route('public.plants')); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Submit Your First Inquiry
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 2: RFQ Requests -->
                <div class="tab-pane fade" id="rfq-requests" role="tabpanel">
                    <?php if($rfqRequests->count() > 0): ?>
                        <div class="inquiries-table-container">
                            <div class="inquiries-table-header">
                                <h6><i class="fas fa-file-invoice me-2"></i>RFQ Requests</h6>
                            </div>
                            <div class="table-scroll-wrapper">
                                <table class="custom-inquiries-table">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Plant Details</th>
                                            <th>Contact Info</th>
                                            <th>Date Submitted</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $rfqRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>#<?php echo e($request->id); ?></td>
                                            <td>
                                                <?php if($request->items_json && is_array($request->items_json)): ?>
                                                    <?php $__currentLoopData = $request->items_json; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="mb-1">
                                                            <strong><?php echo e($item['name'] ?? 'N/A'); ?></strong>
                                                            <?php if(isset($item['code']) && $item['code']): ?>
                                                                <small class="text-muted">(<?php echo e($item['code']); ?>)</small>
                                                            <?php endif; ?>
                                                            <?php if(isset($item['quantity']) && $item['quantity']): ?>
                                                                <br><small>Qty: <?php echo e($item['quantity']); ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No plant details</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div><strong><?php echo e($request->name); ?></strong></div>
                                                <div class="text-muted small"><?php echo e($request->phone); ?></div>
                                                <div class="text-muted small"><?php echo e(Str::limit($request->address, 30)); ?></div>
                                            </td>
                                            <td><?php echo e($request->created_at->format('M d, Y')); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo e($request->status === 'responded' ? 'success' : 'warning'); ?>">
                                                    <?php echo e(ucfirst($request->status)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($request->status === 'responded' && $request->pdf_path): ?>
                                                    <a href="<?php echo e(asset('storage/' . $request->pdf_path)); ?>" 
                                                       class="action-icon action-icon-success" 
                                                       title="Download PDF"
                                                       target="_blank">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($request->status === 'responded' && $request->response_sent_at): ?>
                                                    <a href="<?php echo e(route('user.inquiry.response', $request->id)); ?>" 
                                                       class="action-icon action-icon-primary" 
                                                       title="View Response">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" 
                                                        class="action-icon action-icon-danger" 
                                                        onclick="confirmDeleteInquiry(<?php echo e($request->id); ?>)"
                                                        title="Delete Request">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-inquiry-form-<?php echo e($request->id); ?>" 
                                                      action="<?php echo e(route('user.plant-request.destroy', $request->id)); ?>" 
                                                      method="POST" style="display: none;">
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
                    <?php else: ?>
                        <div class="no-requests">
                            <i class="fas fa-file-invoice"></i>
                            <h5>No RFQ Requests</h5>
                            <p>You haven't submitted any formal quotation requests yet.</p>
                            <a href="<?php echo e(route('public.plants')); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Submit RFQ Request
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 3: Site Visit Requests -->
                <div class="tab-pane fade" id="site-visit-requests" role="tabpanel">
                    <?php if($siteVisitRequests->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $siteVisitRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siteRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="request-card">
                                    <div class="request-header">
                                        <div class="request-date">
                                            <?php echo e($siteRequest->preferred_date->format('M d, Y')); ?>

                                        </div>
                                        <span class="status-badge <?php echo e($siteRequest->status_badge_color); ?>">
                                            <?php echo e(ucfirst($siteRequest->status)); ?>

                                        </span>
                                    </div>
                                    
                                    <div class="request-property">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo e($siteRequest->property_address); ?>

                                    </div>
                                    
                                    <?php if($siteRequest->property_size): ?>
                                    <div class="text-muted small mb-2">
                                        <i class="fas fa-ruler-combined me-1"></i>
                                        Size: <?php echo e($siteRequest->property_size); ?>

                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($siteRequest->project_description): ?>
                                    <div class="text-muted small mb-2">
                                        <strong>Project:</strong> <?php echo e(Str::limit($siteRequest->project_description, 60)); ?>

                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($siteRequest->status === 'approved' && $siteRequest->admin_notes): ?>
                                    <div class="admin-notes">
                                        <strong>Admin Notes:</strong><br>
                                        <?php echo e($siteRequest->admin_notes); ?>

                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($siteRequest->status === 'rejected' && $siteRequest->rejection_reason): ?>
                                    <div class="rejection-reason">
                                        <strong>Rejection Reason:</strong><br>
                                        <?php echo e($siteRequest->rejection_reason); ?>

                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Submitted <?php echo e($siteRequest->created_at->diffForHumans()); ?>

                                        </small>
                                        <div>
                                            <?php if($siteRequest->status === 'approved'): ?>
                                            <a href="<?php echo e(route('client-data.index')); ?>" class="btn btn-sm btn-success me-1">
                                                <i class="fas fa-eye me-1"></i>View Site Data
                                            </a>
                                            <?php endif; ?>
                                            <?php if($siteRequest->status !== 'approved'): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDeleteSiteRequest(<?php echo e($siteRequest->id); ?>)"
                                                    title="Delete Request">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if($siteRequest->status !== 'approved'): ?>
                                    <form id="delete-site-request-form-<?php echo e($siteRequest->id); ?>" 
                                          action="<?php echo e(route('site-visit-requests.destroy.client', $siteRequest->id)); ?>" 
                                          method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="no-requests">
                            <i class="fas fa-calendar-check"></i>
                            <h5>No Site Visit Requests</h5>
                            <p>You haven't requested any site visits yet.</p>
                            <?php if(auth()->user()->isProfileComplete()): ?>
                            <a href="<?php echo e(route('site-visit-requests.create')); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Request Site Visit
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-warning">
                                <i class="fas fa-user-edit me-2"></i>Complete Profile First
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="mb-3">
                <a href="<?php echo e(route('public.plants')); ?>" class="btn btn-success w-100">
                    <i class="fas fa-seedling me-2"></i>Submit Plant Inquiry
                </a>
            </div>
            <div class="mb-3">
                <a href="<?php echo e(route('dashboard.user')); ?>" class="btn btn-outline-success w-100">
                    <i class="fas fa-gauge me-2"></i>Back to Dashboard
                </a>
            </div>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2"><i class="fas fa-info-circle me-2 text-success"></i>About Plant Requests</h6>
                    <ul class="mb-0 small">
                        <li class="mb-2">Submit inquiries from the Home page</li>
                        <li class="mb-2">We'll email you when your quotation is ready</li>
                        <li class="mb-2">Download RFQ documents once responded</li>
                        <li class="mb-0">Track all your plant requests here</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteInquiryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 fs-5">Are you sure you want to delete this request?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteInquiry">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Site Request Confirmation Modal -->
<div class="modal fade" id="deleteSiteRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete Site Visit Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 fs-5">Are you sure you want to delete this site visit request?</p>
                <p class="text-muted">This action cannot be undone.</p>
                <div class="alert alert-info text-start">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> You can only delete pending or rejected requests. Approved requests cannot be deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteSiteRequest">
                    <i class="fas fa-trash me-1"></i>Delete Request
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let inquiryIdToDelete = null;
let siteRequestIdToDelete = null;

function confirmDeleteInquiry(inquiryId) {
    inquiryIdToDelete = inquiryId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteInquiryModal'));
    deleteModal.show();
}

function confirmDeleteSiteRequest(siteRequestId) {
    siteRequestIdToDelete = siteRequestId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteSiteRequestModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteInquiry').addEventListener('click', function() {
    if (inquiryIdToDelete) {
        document.getElementById('delete-inquiry-form-' + inquiryIdToDelete).submit();
    }
});

document.getElementById('confirmDeleteSiteRequest').addEventListener('click', function() {
    if (siteRequestIdToDelete) {
        document.getElementById('delete-site-request-form-' + siteRequestIdToDelete).submit();
    }
});

// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#requestTabs button[data-bs-toggle="tab"]'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    
    // Update tab badges when switching tabs
    function updateTabBadges() {
        const plantInquiriesCount = <?php echo e($plantInquiries->count()); ?>;
        const rfqRequestsCount = <?php echo e($rfqRequests->count()); ?>;
        const siteVisitRequestsCount = <?php echo e($siteVisitRequests->count()); ?>;
        
        // Update badge colors based on active tab
        document.querySelectorAll('.nav-tabs .nav-link').forEach(function(tab) {
            const badge = tab.querySelector('.tab-badge');
            if (tab.classList.contains('active')) {
                badge.style.background = 'rgba(255, 255, 255, 0.2)';
                badge.style.color = 'white';
            } else {
                badge.style.background = '#6c757d';
                badge.style.color = 'white';
            }
        });
    }
    
    // Update badges on tab change
    document.querySelectorAll('#requestTabs button[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', updateTabBadges);
    });
    
    // Initial badge update
    updateTabBadges();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/requests/index.blade.php ENDPATH**/ ?>