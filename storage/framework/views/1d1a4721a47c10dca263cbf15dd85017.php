

<?php $__env->startSection('title', 'User Dashboard - Salenga Farm'); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/loading.css')); ?>" rel="stylesheet">
<style>
    /* Ensure main content area allows full height */
    .container-fluid {
        height: auto !important;
        min-height: auto !important;
        overflow: visible !important;
    }
    
    .row {
        height: auto !important;
        overflow: visible !important;
    }
    
    .col-12, .col-lg-8, .col-lg-4 {
        height: auto !important;
        overflow: visible !important;
    }
    
    /* Override dashboard.css height restrictions for user dashboard */
    body .container-fluid {
        max-height: none !important;
        height: auto !important;
    }
    
    body .main-content {
        height: auto !important;
        min-height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    body .card {
        height: auto !important;
        max-height: none !important;
        min-height: auto !important;
    }
    
    body .card-body {
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-sm i {
        font-size: 0.875rem;
    }
    
    /* Custom table styling - completely independent from Bootstrap */
    .custom-inquiries-table {
        width: 100% !important;
        border-collapse: collapse !important;
        background: white !important;
        margin: 0 !important;
        border: none !important;
        font-family: inherit !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
    }
    
    .custom-inquiries-table * {
        box-sizing: border-box !important;
    }
    
    .custom-inquiries-table thead {
        background: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    .custom-inquiries-table th {
        padding: 12px 16px !important;
        text-align: left !important;
        font-weight: 600 !important;
        color: #495057 !important;
        border-bottom: 1px solid #dee2e6 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        background: #f8f9fa !important;
        vertical-align: middle !important;
    }
    
    .custom-inquiries-table td {
        padding: 12px 16px !important;
        border-bottom: 1px solid #f1f3f4 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        vertical-align: middle !important;
        background: white !important;
    }
    
    .custom-inquiries-table tbody tr:hover td {
        background-color: #f8f9fa !important;
    }
    
    .custom-inquiries-table tbody tr:last-child td {
        border-bottom: none !important;
    }
    
    /* Ensure table container doesn't have height restrictions */
    .inquiries-table-container {
        background: white !important;
        border-radius: 8px !important;
        overflow: visible !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        height: auto !important;
        max-height: none !important;
    }
    
    /* Override any Bootstrap table classes that might interfere */
    .custom-inquiries-table.table,
    .custom-inquiries-table.table-striped,
    .custom-inquiries-table.table-bordered,
    .custom-inquiries-table.table-hover {
        margin-bottom: 0 !important;
        border: none !important;
    }
    
    /* Custom action buttons - uniform size and better alignment */
    .action-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 6px 12px !important;
        margin: 2px !important;
        border: none !important;
        border-radius: 4px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        min-width: 80px !important;
        height: 32px !important;
        white-space: nowrap !important;
        line-height: 1 !important;
        vertical-align: middle !important;
    }
    
    .action-btn i {
        margin-right: 4px !important;
        font-size: 11px !important;
        line-height: 1 !important;
    }
    
    .action-btn-primary {
        background-color: #007bff !important;
        color: white !important;
    }
    
    .action-btn-primary:hover {
        background-color: #0056b3 !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    .action-btn-success {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .action-btn-success:hover {
        background-color: #1e7e34 !important;
        color: white !important;
        text-decoration: none !important;
    }
    
    .action-btn-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }
    
    .action-btn-danger:hover {
        background-color: #c82333 !important;
        color: white !important;
    }
    
    .actions-container {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 4px !important;
        align-items: center !important;
        justify-content: flex-start !important;
    }
    
    /* Status badges - consistent styling */
    .status-badge {
        padding: 4px 8px !important;
        border-radius: 12px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        text-transform: uppercase !important;
        display: inline-block !important;
        line-height: 1.2 !important;
        white-space: nowrap !important;
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
    
    .status-badge.secondary {
        background-color: #e2e3e5 !important;
        color: #383d41 !important;
        border: 1px solid #d6d8db !important;
    }
    
    /* Table container - ensure proper display */
    .inquiries-table-container {
        background: white !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        height: auto !important;
        max-height: none !important;
        width: 100% !important;
        margin-bottom: 20px !important;
    }
    
    /* Table wrapper for scrolling */
    .table-scroll-wrapper {
        max-height: 420px !important; /* Approximately 7 rows (60px each) */
        overflow-y: auto !important;
        overflow-x: hidden !important;
        border-radius: 0 0 8px 8px !important;
    }
    
    /* Custom scrollbar for table */
    .table-scroll-wrapper::-webkit-scrollbar {
        width: 6px !important;
    }
    
    .table-scroll-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
        border-radius: 3px !important;
    }
    
    .table-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #c1c1c1 !important;
        border-radius: 3px !important;
    }
    
    .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8 !important;
    }
    
    /* Ensure table header stays visible */
    .custom-inquiries-table thead {
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
        background: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    /* Ensure each row has consistent height for scrolling calculation */
    .custom-inquiries-table tbody tr {
        height: 60px !important;
        min-height: 60px !important;
    }
    
    .inquiries-table-header {
        background: #f8f9fa !important;
        padding: 16px 20px !important;
        border-bottom: 1px solid #dee2e6 !important;
        border-radius: 8px 8px 0 0 !important;
    }
    
    .inquiries-table-header h6 {
        margin: 0 !important;
        font-weight: 600 !important;
        color: #495057 !important;
        font-size: 16px !important;
    }
    
    /* Ensure the table wrapper doesn't restrict height */
    .table-responsive {
        overflow-x: auto !important;
        overflow-y: visible !important;
        height: auto !important;
        max-height: none !important;
    }
    
    /* Remove any Bootstrap table responsive restrictions */
    .table-responsive .custom-inquiries-table {
        margin-bottom: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
    }
    
    .no-inquiries {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .no-inquiries i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-gauge me-2 text-success"></i>Dashboard</h4>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="inquiries-table-container">
                <div class="inquiries-table-header">
                    <h6>Recent Inquiries</h6>
                </div>
                
                <?php if($requests->count() > 0): ?>
                <div class="table-scroll-wrapper">
                    <table class="custom-inquiries-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 140px;">Inquiry Date</th>
                                <th style="width: 120px;">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>#<?php echo e($r->id); ?></strong></td>
                                <td><?php echo e(optional($r->created_at)->format('M d, Y') ?? '—'); ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($r->status ?? 'pending');
                                        $displayStatus = $status === 'sent' ? 'received' : $status;
                                        $badgeClass = $status === 'responded' ? 'success' : ($status === 'pending' ? 'warning' : 'secondary');
                                    ?>
                                    <span class="status-badge <?php echo e($badgeClass); ?>">
                                        <?php echo e(ucfirst($displayStatus)); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="actions-container">
                                        <?php if($status === 'responded'): ?>
                                            <a href="<?php echo e(route('user.inquiry.response', $r->id)); ?>" class="action-btn action-btn-primary">
                                                <i class="fas fa-eye"></i>View Response
                                            </a>
                                            <?php if($r->request_type === 'client' && $r->pdf_path): ?>
                                                <a href="<?php echo e(route('requests.download-pdf', $r->id)); ?>" class="action-btn action-btn-success">
                                                    <i class="fas fa-download"></i>Download RFQ
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <form id="delete-inquiry-form-<?php echo e($r->id); ?>" action="<?php echo e(route('user.requests.destroy', $r->id)); ?>" method="POST" style="display: none;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                        <button type="button" class="action-btn action-btn-danger" onclick="confirmDeleteInquiry(<?php echo e($r->id); ?>)">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="no-inquiries">
                    <i class="fas fa-seedling"></i>
                    <p><strong>No inquiries yet</strong></p>
                    <p>Go to <a href="<?php echo e(route('public.plants')); ?>">Home</a> to place an inquiry.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="mb-3">
                <a href="<?php echo e(route('public.plants')); ?>" class="btn btn-success w-100">
                    <i class="fas fa-rotate-left me-2"></i>Return to Home to Inquire
                </a>
            </div>
            <?php if(!auth()->user()->is_client): ?>
            <div class="mb-3">
                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#clientRequestModal">
                    <i class="fas fa-user-plus me-2"></i>Request to be a Client
                </button>
            </div>
            <?php endif; ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-2">Tips</h6>
                    <ul class="mb-0">
                        <li>To inquire about plants, go back to Home and use the Inquiry button.</li>
                        <li>We’ll email you when your quotation is ready.</li>
                        <li>This page shows a simple summary of your recent inquiries.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Client Request Modal -->
<div class="modal fade" id="clientRequestModal" tabindex="-1" aria-labelledby="clientRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="clientRequestModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Request to Become a Client
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Benefits of becoming a client:</strong>
                </div>
                <ul class="mb-3">
                    <li>Access to detailed pricing and quotations</li>
                    <li>Priority order processing</li>
                    <li>Flexible delivery arrangements</li>
                    <li>Ongoing support for all your plant needs</li>
                    <li>Access to exclusive plant varieties</li>
                </ul>
                
                <form id="clientRequestForm" method="POST" action="<?php echo e(route('client-request.submit')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Account Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Account Type <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_type" id="accountIndividual" value="individual" checked onchange="toggleAccountFields()">
                                    <label class="form-check-label" for="accountIndividual">
                                        <i class="fas fa-user me-1"></i> Individual / Homeowner
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_type" id="accountCompany" value="company" onchange="toggleAccountFields()">
                                    <label class="form-check-label" for="accountCompany">
                                        <i class="fas fa-building me-1"></i> Company / Organization
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Individual Fields -->
                    <div id="individualFields">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="full_name_individual" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name_individual" name="full_name" value="<?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_individual" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="contact_individual" name="contact_number" value="<?php echo e(auth()->user()->contact_number); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address_individual" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address_individual" name="address" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="email_individual" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_individual" name="email" value="<?php echo e(auth()->user()->email); ?>" readonly>
                        </div>
                    </div>

                    <!-- Company Fields -->
                    <div id="companyFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="full_name_company" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name_company" name="full_name_company" value="<?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_company" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="contact_company" name="contact_number_company" value="<?php echo e(auth()->user()->contact_number); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender_company" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender_company" name="gender_company">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo e(auth()->user()->company_name); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="company_address" class="form-label">Company Address (Optional)</label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="email_company" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_company" name="email_company" value="<?php echo e(auth()->user()->email); ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="request_message" class="form-label">Additional Message (Optional)</label>
                        <textarea class="form-control" id="request_message" name="message" rows="3" placeholder="Tell us about your business or why you'd like to become a client..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>Your request will be sent to our team for review. We'll contact you via email within 1-2 business days.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="clientRequestForm" class="btn btn-success" id="submitRequestBtn">
                    <span id="submitBtnText"><i class="fas fa-paper-plane me-2"></i>Submit Request</span>
                    <span id="submitBtnLoader" style="display: none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Submitting...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="clientRequestSuccessModal" tabindex="-1" aria-labelledby="clientRequestSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="clientRequestSuccessModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Request Sent Successfully!
                </h5>
            </div>
            <div class="modal-body text-center py-5">
                <div class="success-checkmark mb-4">
                    <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="checkmark-circle"></div>
                        <div class="icon-fix"></div>
                    </div>
                </div>
                <h4 class="text-success mb-3">Thank You!</h4>
                <p class="text-muted mb-0">Your client request has been sent!</p>
                <p class="text-muted">We've received your request to become a client. Our team will review your information and contact you via email within 1-2 business days.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>Continue
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Animated Success Checkmark */
.success-checkmark {
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.check-icon {
    width: 80px;
    height: 80px;
    position: relative;
    border-radius: 50%;
    box-sizing: content-box;
    border: 4px solid #4caf50;
}

.check-icon::before {
    top: 3px;
    left: -2px;
    width: 30px;
    transform-origin: 100% 50%;
    border-radius: 100px 0 0 100px;
}

.check-icon::after {
    top: 0;
    left: 30px;
    width: 60px;
    transform-origin: 0 50%;
    border-radius: 0 100px 100px 0;
    animation: rotate-circle 4.25s ease-in;
}

.icon-line {
    height: 5px;
    background-color: #4caf50;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.icon-line.line-tip {
    top: 46px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.icon-line.line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.checkmark-circle {
    top: -4px;
    left: -4px;
    z-index: 10;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    position: absolute;
    box-sizing: content-box;
    border: 4px solid rgba(76, 175, 80, 0.5);
}

.icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    transform: rotate(-45deg);
    background-color: #fff;
}

@keyframes rotate-circle {
    0% {
        transform: rotate(-45deg);
    }
    5% {
        transform: rotate(-45deg);
    }
    12% {
        transform: rotate(-405deg);
    }
    100% {
        transform: rotate(-405deg);
    }
}

@keyframes icon-line-tip {
    0% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    54% {
        width: 0;
        left: 1px;
        top: 19px;
    }
    70% {
        width: 50px;
        left: -8px;
        top: 37px;
    }
    84% {
        width: 17px;
        left: 21px;
        top: 48px;
    }
    100% {
        width: 25px;
        left: 14px;
        top: 45px;
    }
}

@keyframes icon-line-long {
    0% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    65% {
        width: 0;
        right: 46px;
        top: 54px;
    }
    84% {
        width: 55px;
        right: 0px;
        top: 35px;
    }
    100% {
        width: 47px;
        right: 8px;
        top: 38px;
    }
}
</style>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteInquiryModal" tabindex="-1" aria-labelledby="deleteInquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteInquiryModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle text-danger delete-icon"></i>
                </div>
                <p class="text-center fs-5">Are you sure you want to delete this inquiry?</p>
                <p class="text-center text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> No, Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteInquiry">
                    <i class="fas fa-trash me-1"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Delete Modal Icon */
.delete-icon {
    font-size: 2rem;
}
</style>

<script src="<?php echo e(asset('js/loading.js')); ?>"></script>
<script>
let inquiryIdToDelete = null;

function confirmDeleteInquiry(inquiryId) {
    inquiryIdToDelete = inquiryId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteInquiryModal'));
    deleteModal.show();
}

document.getElementById('confirmDeleteInquiry').addEventListener('click', function() {
    if (inquiryIdToDelete) {
        document.getElementById('delete-inquiry-form-' + inquiryIdToDelete).submit();
    }
});

// Check if there's a success message and show modal
<?php if(session('success')): ?>
    document.addEventListener('DOMContentLoaded', function() {
        var successModal = new bootstrap.Modal(document.getElementById('clientRequestSuccessModal'));
        successModal.show();
    });
<?php endif; ?>

// Form submission with loading screen
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('clientRequestForm');
    const submitBtn = document.getElementById('submitRequestBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoader = document.getElementById('submitBtnLoader');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Show button loader
            submitBtnText.style.display = 'none';
            submitBtnLoader.style.display = 'inline-block';
            submitBtn.disabled = true;
            
            // Show standardized loading overlay
            setTimeout(function() {
                LoadingManager.show('Submitting your request...');
            }, 300);
        });
    }
});

function toggleAccountFields() {
    const isIndividual = document.getElementById('accountIndividual').checked;
    const individualFields = document.getElementById('individualFields');
    const companyFields = document.getElementById('companyFields');
    
    if (isIndividual) {
        individualFields.style.display = 'block';
        companyFields.style.display = 'none';
        // Enable individual fields
        document.querySelectorAll('#individualFields input, #individualFields select, #individualFields textarea').forEach(el => {
            if (!el.readOnly) el.required = true;
        });
        // Disable company fields
        document.querySelectorAll('#companyFields input:not([readonly]), #companyFields select, #companyFields textarea').forEach(el => {
            el.required = false;
        });
    } else {
        individualFields.style.display = 'none';
        companyFields.style.display = 'block';
        // Disable individual fields
        document.querySelectorAll('#individualFields input:not([readonly]), #individualFields select, #individualFields textarea').forEach(el => {
            el.required = false;
        });
        // Enable company fields (except optional ones)
        document.getElementById('full_name_company').required = true;
        document.getElementById('contact_company').required = true;
        document.getElementById('gender_company').required = true;
        document.getElementById('company_name').required = true;
    }
}
</script>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/dashboard/user.blade.php ENDPATH**/ ?>