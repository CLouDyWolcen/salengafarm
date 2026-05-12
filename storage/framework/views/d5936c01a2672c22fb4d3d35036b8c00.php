<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Edit Site Visit - Plant Inventory</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .form-section {
            background: #f8f9fa;
            border-left: 4px solid #198754;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
        }
        .form-section h5 {
            color: #198754;
            margin-bottom: 1rem;
        }
        #map {
            height: 300px;
            border-radius: 0.375rem;
        }
        .coordinate-display {
            background: #e9ecef;
            padding: 0.5rem;
            border-radius: 0.375rem;
            font-family: monospace;
        }
        .checkbox-group {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            border-radius: 0.375rem;
            background-color: #ffffff;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.25rem;
        }
        .form-check {
            margin-bottom: 0.25rem;
        }
        .form-check-label {
            margin-left: 0.25rem;
            font-size: 0.9em;
        }
        /* Fix main container positioning */
        .main-content {
            margin-left: 240px;
            padding: 1rem 2rem;
            max-width: calc(100vw - 260px);
            overflow-x: hidden;
        }
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .checkbox-group {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                max-width: 100vw;
                padding: 1rem;
            }
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
        /* Larger radios/checkboxes for Physical Factors, Topography, Geotechnical Soils, Utilities, Immediate Surroundings, Tools, Additional Services */
        .physical-factors input[type="radio"], 
        .physical-factors input[type="checkbox"],
        .topography input[type="radio"],
        .topography input[type="checkbox"],
        .geotechnical-soils input[type="radio"],
        .geotechnical-soils input[type="checkbox"],
        .utilities input[type="radio"],
        .utilities input[type="checkbox"],
        .immediate-surroundings input[type="radio"],
        .immediate-surroundings input[type="checkbox"],
        .tools-checklist input[type="checkbox"],
        .additional-services input[type="radio"],
        .additional-services input[type="checkbox"] {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: #198754;
        }
        
        /* Render radios as square checkboxes (visual only) in tabular sections */
        .physical-factors input[type="radio"],
        .topography input[type="radio"],
        .geotechnical-soils input[type="radio"],
        .utilities input[type="radio"],
        .immediate-surroundings input[type="radio"],
        .additional-services input[type="radio"] {
            -webkit-appearance: none;
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #198754;
            border-radius: 0.25rem; /* square corners like a checkbox */
            background-color: #fff;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }
        .physical-factors input[type="radio"]:checked,
        .topography input[type="radio"]:checked,
        .geotechnical-soils input[type="radio"]:checked,
        .utilities input[type="radio"]:checked,
        .immediate-surroundings input[type="radio"]:checked,
        .additional-services input[type="radio"]:checked {
            background-color: #198754;
            border-color: #198754;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="white"><path d="M6.173 11.414L2.757 8l1.414-1.414L6.173 8.586l5.657-5.657L13.243 4.343z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 12px 12px;
        }
        .physical-factors input[type="radio"]:focus,
        .topography input[type="radio"]:focus,
        .geotechnical-soils input[type="radio"]:focus,
        .utilities input[type="radio"]:focus,
        .immediate-surroundings input[type="radio"]:focus,
        .additional-services input[type="radio"]:focus {
            outline: 2px solid rgba(25, 135, 84, 0.3);
            outline-offset: 2px;
        }

        /* Tools checkboxes: green border + filled green when checked */
        .tools-checklist input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #198754;
            border-radius: 0.25rem;
            background-color: #fff;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }
        .tools-checklist input[type="checkbox"]:checked {
            background-color: #198754;
            border-color: #198754;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="white"><path d="M6.173 11.414L2.757 8l1.414-1.414L6.173 8.586l5.657-5.657L13.243 4.343z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 12px 12px;
        }
        .tools-checklist input[type="checkbox"]:focus {
            outline: 2px solid rgba(25, 135, 84, 0.3);
            outline-offset: 2px;
        }
        
        /* Required field validation styling */
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        /* Smooth fade out for auto-dismissing alerts */
        .alert.fade {
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-light">
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <div class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-map-marker-alt me-2 text-success"></i>Edit Site Visit</h2>
                    <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Site Visits
                    </a>
                </div>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info alert-dismissible fade show" role="alert" id="info-note-alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> Fields marked with an asterisk (*) are required. Please ensure all required information is filled in before updating.
                    <small class="ms-2 text-muted" id="info-note-timer">(Auto-dismiss in <span id="countdown">5</span>s)</small>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <form action="<?php echo e(route('site-visits.update', $siteVisit)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Location Section -->
                    <div class="form-section">
                        <h5><i class="fas fa-map-pin me-2"></i>Location Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Click on the map to set location:</label>
                                <div class="mb-2">
                                    <button type="button" id="currentLocationBtn" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-location-arrow me-2"></i>Use Current Location
                                    </button>
                                    <small class="text-muted ms-2">Or click anywhere on the map</small>
                                    <div class="text-info mt-1">
                                        <small><i class="fas fa-info-circle me-1"></i>If location is inaccurate, manually click on the correct spot on the map</small>
                                    </div>
                                </div>
                                <div id="map"></div>
                                <div class="coordinate-display mt-2">
                                    <strong>Selected Coordinates:</strong>
                                    <span id="coordinates"><?php echo e($siteVisit->latitude); ?>, <?php echo e($siteVisit->longitude); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude *</label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude" 
                                       value="<?php echo e(old('latitude', $siteVisit->latitude)); ?>" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude *</label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude" 
                                       value="<?php echo e(old('longitude', $siteVisit->longitude)); ?>" required readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="location_address" class="form-label">Address</label>
                                <textarea class="form-control" name="location_address" rows="2"><?php echo e(old('location_address', $siteVisit->location_address)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-user me-2"></i>Client Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="user_id" class="form-label">Link Existing User (optional)</label>
                                <select class="form-select" id="user_id" name="user_id">
                                    <option value="">Select user</option>
                                    <?php $__currentLoopData = ($clients ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php ($displayName = $u->name ?? trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''))); ?>
                                        <option value="<?php echo e($u->id); ?>"
                                                data-name="<?php echo e($displayName); ?>"
                                                data-email="<?php echo e($u->email); ?>"
                                                data-contact="<?php echo e($u->contact_number); ?>"
                                                <?php echo e(old('user_id', $siteVisit->user_id) == $u->id ? 'selected' : ''); ?>>
                                            <?php echo e($displayName); ?> (<?php echo e($u->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted">Selecting a user will auto-fill Client Name, Email, and Contact Number.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="client" class="form-label">Client Name *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['client'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="client" name="client" value="<?php echo e(old('client', $siteVisit->client)); ?>" required>
                                <?php $__errorArgs = ['client'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_number" name="contact_number" value="<?php echo e(old('contact_number', $siteVisit->contact_number)); ?>" required>
                                <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $siteVisit->email)); ?>" required>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="job_no" class="form-label">Job Number</label>
                                <input type="text" class="form-control" name="job_no" value="<?php echo e(old('job_no', $siteVisit->job_no)); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Project Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-project-diagram me-2"></i>Project Information</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="project_code" class="form-label">Project Code</label>
                                <input type="text" class="form-control" name="project_code" value="<?php echo e(old('project_code', $siteVisit->project_code)); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="project_no" class="form-label">Project Number</label>
                                <input type="text" class="form-control" name="project_no" value="<?php echo e(old('project_no', $siteVisit->project_no)); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="landscape_area" class="form-label">Landscape Area</label>
                                <input type="text" class="form-control" name="landscape_area" value="<?php echo e(old('landscape_area', $siteVisit->landscape_area)); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Site Location *</label>
                                <input type="text" class="form-control" name="location" value="<?php echo e(old('location', $siteVisit->location)); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="site_inspector" class="form-label">Site Inspector *</label>
                                <input type="text" class="form-control" name="site_inspector" value="<?php echo e(old('site_inspector', $siteVisit->site_inspector)); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="visit_date" class="form-label">Visit Date *</label>
                                <input type="date" class="form-control" name="visit_date" value="<?php echo e(old('visit_date', $siteVisit->visit_date->toDateString())); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending" <?php echo e(old('status', $siteVisit->status) == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="completed" <?php echo e(old('status', $siteVisit->status) == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="follow_up" <?php echo e(old('status', $siteVisit->status) == 'follow_up' ? 'selected' : ''); ?>>Follow-up Required</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="client_data_open" name="client_data_open" value="1"
                                           <?php echo e(old('client_data_open', $siteVisit->client_data_open) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="client_data_open">
                                        Open Client Data for uploads (clients can start uploading now)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-section">
                        <h5><i class="fas fa-file-contract me-2"></i>Terms and Conditions</h5>
                        <div class="mb-3">
                            <textarea class="form-control" name="terms_and_conditions" rows="6" 
                                      placeholder="Enter terms and conditions for this site visit or project..."><?php echo e(old('terms_and_conditions', $siteVisit->terms_and_conditions ?? '')); ?></textarea>
                        </div>
                    </div>

                    <!-- Design Quotation -->
                    <div class="form-section">
                        <h5><i class="fas fa-calculator me-2"></i>Design Quotation</h5>
                        <div class="mb-3">
                            <textarea class="form-control" name="design_quotation" rows="6" 
                                      placeholder="Enter design quotation details, pricing, and specifications..."><?php echo e(old('design_quotation', $siteVisit->design_quotation ?? '')); ?></textarea>
                        </div>
                    </div>

                    <!-- Scope of Work (Static Text) -->
                    <div class="form-section">
                        <h5><i class="fas fa-tasks me-2"></i>Scope of Work</h5>
                        <?php echo $__env->make('site-visits._scope_of_work_text', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <!-- Physical Factors -->
                    <div class="form-section">
                        <h5><i class="fas fa-cloud-sun me-2"></i>Physical Factors</h5>
                        <div class="mb-2 text-muted">Climate</div>
                        <?php ($pf = old('physical_factors', $siteVisit->physical_factors ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle physical-factors">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Prevailing Winds</td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[prevailing_winds][value]" value="yes" 
                                                   <?php echo e(data_get($pf, 'prevailing_winds.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[prevailing_winds][value]" value="no" 
                                                   <?php echo e(data_get($pf, 'prevailing_winds.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="physical_factors[prevailing_winds][remarks]" 
                                                   value="<?php echo e(data_get($pf, 'prevailing_winds.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">B</td>
                                        <td>Solar Orientation / North</td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[solar_orientation][value]" value="yes" 
                                                   <?php echo e(data_get($pf, 'solar_orientation.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[solar_orientation][value]" value="no" 
                                                   <?php echo e(data_get($pf, 'solar_orientation.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="physical_factors[solar_orientation][remarks]" 
                                                   value="<?php echo e(data_get($pf, 'solar_orientation.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">C</td>
                                        <td>Humidity</td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[humidity][value]" value="yes" 
                                                   <?php echo e(data_get($pf, 'humidity.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="physical_factors[humidity][value]" value="no" 
                                                   <?php echo e(data_get($pf, 'humidity.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="physical_factors[humidity][remarks]" 
                                                   value="<?php echo e(data_get($pf, 'humidity.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="physical_factors[notes]" rows="3" placeholder="Additional notes about climate conditions"><?php echo e(data_get($pf, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Topography -->
                    <div class="form-section">
                        <h5><i class="fas fa-mountain me-2"></i>Topography</h5>
                        <?php ($tp = old('topography', $siteVisit->topography ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle topography">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Legal Properties Description</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[legal_properties_description][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'legal_properties_description.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[legal_properties_description][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'legal_properties_description.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[legal_properties_description][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'legal_properties_description.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">B</td>
                                        <td>Topographic Maps & Aerial Photos</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[topographic_maps_aerial_photos][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'topographic_maps_aerial_photos.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[topographic_maps_aerial_photos][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'topographic_maps_aerial_photos.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[topographic_maps_aerial_photos][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'topographic_maps_aerial_photos.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">C</td>
                                        <td>Existing Access and Circulation</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_access_circulation][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'existing_access_circulation.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_access_circulation][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'existing_access_circulation.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[existing_access_circulation][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'existing_access_circulation.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">D</td>
                                        <td>Vegetation</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[vegetation][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'vegetation.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[vegetation][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'vegetation.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[vegetation][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'vegetation.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">E</td>
                                        <td>Existing Water Bodies</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_water_bodies][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'existing_water_bodies.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_water_bodies][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'existing_water_bodies.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[existing_water_bodies][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'existing_water_bodies.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">F</td>
                                        <td>Drainage Canals</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[drainage_canals][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'drainage_canals.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[drainage_canals][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'drainage_canals.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[drainage_canals][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'drainage_canals.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">G</td>
                                        <td>Existing Waterway</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_waterway][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'existing_waterway.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[existing_waterway][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'existing_waterway.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[existing_waterway][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'existing_waterway.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">H</td>
                                        <td>Unique Site Features</td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[unique_site_features][value]" value="yes" 
                                                   <?php echo e(data_get($tp, 'unique_site_features.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="topography[unique_site_features][value]" value="no" 
                                                   <?php echo e(data_get($tp, 'unique_site_features.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="topography[unique_site_features][remarks]" 
                                                   value="<?php echo e(data_get($tp, 'unique_site_features.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="topography[notes]" rows="3" placeholder="Additional notes about topography"><?php echo e(data_get($tp, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Geotechnical Soils -->
                    <div class="form-section">
                        <h5><i class="fas fa-microscope me-2"></i>Geotechnical Soils</h5>
                        <?php ($gs = old('geotechnical_soils', $siteVisit->geotechnical_soils ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle geotechnical-soils">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Basic Soil Type</td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[basic_soil_type][value]" value="yes" 
                                                   <?php echo e(data_get($gs, 'basic_soil_type.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[basic_soil_type][value]" value="no" 
                                                   <?php echo e(data_get($gs, 'basic_soil_type.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="geotechnical_soils[basic_soil_type][remarks]" 
                                                   value="<?php echo e(data_get($gs, 'basic_soil_type.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">B</td>
                                        <td>Soil Conditions</td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[soil_conditions][value]" value="yes" 
                                                   <?php echo e(data_get($gs, 'soil_conditions.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[soil_conditions][value]" value="no" 
                                                   <?php echo e(data_get($gs, 'soil_conditions.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="geotechnical_soils[soil_conditions][remarks]" 
                                                   value="<?php echo e(data_get($gs, 'soil_conditions.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">C</td>
                                        <td>Earthfill Requirement</td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[earthfill_requirement][value]" value="yes" 
                                                   <?php echo e(data_get($gs, 'earthfill_requirement.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="geotechnical_soils[earthfill_requirement][value]" value="no" 
                                                   <?php echo e(data_get($gs, 'earthfill_requirement.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="geotechnical_soils[earthfill_requirement][remarks]" 
                                                   value="<?php echo e(data_get($gs, 'earthfill_requirement.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="geotechnical_soils[notes]" rows="3" placeholder="Additional notes about geotechnical soils"><?php echo e(data_get($gs, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Utilities -->
                    <div class="form-section">
                        <h5><i class="fas fa-plug me-2"></i>Utilities</h5>
                        <?php ($ut = old('utilities', $siteVisit->utilities ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle utilities">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Potable Water</td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[potable_water][value]" value="yes" 
                                                   <?php echo e(data_get($ut, 'potable_water.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[potable_water][value]" value="no" 
                                                   <?php echo e(data_get($ut, 'potable_water.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="utilities[potable_water][remarks]" 
                                                   value="<?php echo e(data_get($ut, 'potable_water.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">B</td>
                                        <td>Electricity</td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[electricity][value]" value="yes" 
                                                   <?php echo e(data_get($ut, 'electricity.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[electricity][value]" value="no" 
                                                   <?php echo e(data_get($ut, 'electricity.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="utilities[electricity][remarks]" 
                                                   value="<?php echo e(data_get($ut, 'electricity.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">C</td>
                                        <td>Storm Drainage</td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[storm_drainage][value]" value="yes" 
                                                   <?php echo e(data_get($ut, 'storm_drainage.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="utilities[storm_drainage][value]" value="no" 
                                                   <?php echo e(data_get($ut, 'storm_drainage.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="utilities[storm_drainage][remarks]" 
                                                   value="<?php echo e(data_get($ut, 'storm_drainage.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="utilities[notes]" rows="3" placeholder="Additional notes about utilities"><?php echo e(data_get($ut, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Immediate Surroundings -->
                    <div class="form-section">
                        <h5><i class="fas fa-city me-2"></i>Immediate Surroundings</h5>
                        <?php ($is = old('immediate_surroundings', $siteVisit->immediate_surroundings ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle immediate-surroundings">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Neighborhood Structures</td>
                                        <td class="text-center">
                                            <input type="radio" name="immediate_surroundings[neighborhood_structures][value]" value="yes" 
                                                   <?php echo e(data_get($is, 'neighborhood_structures.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="immediate_surroundings[neighborhood_structures][value]" value="no" 
                                                   <?php echo e(data_get($is, 'neighborhood_structures.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="immediate_surroundings[neighborhood_structures][remarks]" 
                                                   value="<?php echo e(data_get($is, 'neighborhood_structures.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="immediate_surroundings[notes]" rows="3" placeholder="Additional notes about immediate surroundings"><?php echo e(data_get($is, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Additional Services -->
                    <div class="form-section">
                        <h5><i class="fas fa-people-carry-box me-2"></i>Additional Services</h5>
                        <?php ($as = old('additional_services', $siteVisit->additional_services ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle additional-services">
                                <thead>
                                    <tr>
                                        <th style="width: 4rem;">&nbsp;</th>
                                        <th>Item</th>
                                        <th style="width: 6rem;" class="text-center">Yes</th>
                                        <th style="width: 6rem;" class="text-center">No</th>
                                        <th style="width: 40%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">A</td>
                                        <td>Land Preperation</td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[land_preparation][value]" value="yes" 
                                                   <?php echo e(data_get($as, 'land_preparation.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[land_preparation][value]" value="no" 
                                                   <?php echo e(data_get($as, 'land_preparation.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="additional_services[land_preparation][remarks]" 
                                                   value="<?php echo e(data_get($as, 'land_preparation.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">B</td>
                                        <td>Grading</td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[grading][value]" value="yes" 
                                                   <?php echo e(data_get($as, 'grading.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[grading][value]" value="no" 
                                                   <?php echo e(data_get($as, 'grading.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="additional_services[grading][remarks]" 
                                                   value="<?php echo e(data_get($as, 'grading.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">C</td>
                                        <td>Leveling</td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[leveling][value]" value="yes" 
                                                   <?php echo e(data_get($as, 'leveling.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[leveling][value]" value="no" 
                                                   <?php echo e(data_get($as, 'leveling.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="additional_services[leveling][remarks]" 
                                                   value="<?php echo e(data_get($as, 'leveling.remarks')); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">D</td>
                                        <td>Stacking</td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[stacking][value]" value="yes" 
                                                   <?php echo e(data_get($as, 'stacking.value') === 'yes' ? 'checked' : ''); ?>>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="additional_services[stacking][value]" value="no" 
                                                   <?php echo e(data_get($as, 'stacking.value') === 'no' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="additional_services[stacking][remarks]" 
                                                   value="<?php echo e(data_get($as, 'stacking.remarks')); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="additional_services[notes]" rows="3" placeholder="Additional notes about additional services"><?php echo e(data_get($as, 'notes')); ?></textarea>
                        </div>
                    </div>

                    <!-- Tools -->
                    <div class="form-section">
                        <h5><i class="fas fa-toolbox me-2"></i>Tools</h5>
                        <?php ($tl = old('tools_checklist', $siteVisit->tools_checklist ?? [])); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle tools-checklist">
                                <thead>
                                    <tr>
                                        <th style="width: 5rem;" class="text-center">Check</th>
                                        <th>Safety</th>
                                        <th style="width: 5rem;" class="text-center">Check</th>
                                        <th>Documentation</th>
                                        <th style="width: 5rem;" class="text-center">Check</th>
                                        <th>Drawing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[safety][vest]" 
                                                   <?php echo e(data_get($tl, 'safety.vest') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Vest</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[documentation][scale]" 
                                                   <?php echo e(data_get($tl, 'documentation.scale') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Scale</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[drawing][clip_board]" 
                                                   <?php echo e(data_get($tl, 'drawing.clip_board') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Clip Board</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[safety][hard_hat]" 
                                                   <?php echo e(data_get($tl, 'safety.hard_hat') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Hard Hat</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[documentation][steel_tape]" 
                                                   <?php echo e(data_get($tl, 'documentation.steel_tape') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Steel Tape</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[drawing][plans]" 
                                                   <?php echo e(data_get($tl, 'drawing.plans') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Plans</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[safety][safety_shoes]" 
                                                   <?php echo e(data_get($tl, 'safety.safety_shoes') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Safety Shoes</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[documentation][tape_measures_long]" 
                                                   <?php echo e(data_get($tl, 'documentation.tape_measures_long') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Tape Measures Long</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[drawing][bond_papers]" 
                                                   <?php echo e(data_get($tl, 'drawing.bond_papers') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Bond Papers</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[safety][mask]" 
                                                   <?php echo e(data_get($tl, 'safety.mask') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Mask</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[documentation][tape_measures_short]" 
                                                   <?php echo e(data_get($tl, 'documentation.tape_measures_short') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Tape Measures Short</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[drawing][pens]" 
                                                   <?php echo e(data_get($tl, 'drawing.pens') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Pens</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[safety][face_shield_medical_cert]" 
                                                   <?php echo e(data_get($tl, 'safety.face_shield_medical_cert') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Face Shield. Medical Cert</td>
                                        <td class="text-center">
                                            <input type="checkbox" value="1" name="tools_checklist[documentation][camera]" 
                                                   <?php echo e(data_get($tl, 'documentation.camera') ? 'checked' : ''); ?>>
                                        </td>
                                        <td>Camera</td>
                                        <td class="text-center">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if(false): ?>
                    <!-- Client Data (Multi-file Uploads) -->
                    <div class="form-section">
                        <h5><i class="fas fa-user-check me-2"></i>Client Data</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Item</th>
                                        <th>Upload Files</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Land title</td>
                                        <td>
                                            <input type="file" name="clients_data[land_title][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sketch Plan</td>
                                        <td>
                                            <input type="file" name="clients_data[sketch_plan][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Topogrophy</td>
                                        <td>
                                            <input type="file" name="clients_data[topogrophy][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tree map</td>
                                        <td>
                                            <input type="file" name="clients_data[tree_map][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Site development plan (SDP)</td>
                                        <td>
                                            <input type="file" name="clients_data[sdp][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Master Development Plant (MDP)</td>
                                        <td>
                                            <input type="file" name="clients_data[mdp][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Drone Map</td>
                                        <td>
                                            <input type="file" name="clients_data[drone_map][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Proposal Checklist (Multi-file Uploads) -->
                    <div class="form-section">
                        <h5><i class="fas fa-clipboard-check me-2"></i>Proposal Checklist</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Item</th>
                                        <th>Upload Files</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Concept Board</td>
                                        <td>
                                            <input type="file" name="proposal_documents[concept_board][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Design Service Agreement</td>
                                        <td>
                                            <input type="file" name="proposal_documents[design_service_agreement][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Build Service Agreement</td>
                                        <td>
                                            <input type="file" name="proposal_documents[build_service_agreement][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Design Quotation</td>
                                        <td>
                                            <input type="file" name="proposal_documents[design_quotation][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Build Quotation: Rough Estimate</td>
                                        <td>
                                            <input type="file" name="proposal_documents[build_quotation_rough_estimate][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Supervision Quotation</td>
                                        <td>
                                            <input type="file" name="proposal_documents[supervision_quotation][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Bill of Materials (BOQ)</td>
                                        <td>
                                            <input type="file" name="proposal_documents[bill_of_materials][]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php endif; ?>
                    <!-- Media Files -->
                    <div class="form-section">
                        <h5><i class="fas fa-camera me-2"></i>Media Files</h5>
                        <div class="mb-3">
                            <label for="media_files" class="form-label">Upload Photos/Videos</label>
                            <input type="file" class="form-control" name="media_files[]" accept="image/*,video/*" multiple>
                            <div class="form-text">You can upload multiple images (JPG, PNG) or videos (MP4, MOV). Max size: 10MB per file.</div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="form-section">
                        <h5><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" rows="4"><?php echo e(old('notes', $siteVisit->notes)); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;

        // Initialize the map when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            setupCurrentLocationButton();
            setupToggleableRadios();
            
            // Auto-dismiss info note after 5 seconds
            const infoNoteAlert = document.getElementById('info-note-alert');
            if (infoNoteAlert) {
                let countdown = 5;
                const countdownSpan = document.getElementById('countdown');
                
                // Update countdown every second
                const countdownInterval = setInterval(() => {
                    countdown--;
                    if (countdownSpan) {
                        countdownSpan.textContent = countdown;
                    }
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                    }
                }, 1000);
                
                // Dismiss after 5 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(infoNoteAlert);
                    bsAlert.close();
                }, 5000);
            }
        });

        function setupCurrentLocationButton() {
            const currentLocationBtn = document.getElementById('currentLocationBtn');
            
            currentLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    // Show loading state
                    currentLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting location...';
                    currentLocationBtn.disabled = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Add marker and update form
                            addMarker(lat, lng);
                            
                            // Center map on current location
                            map.setView([lat, lng], 16);
                            
                            // Try to get address from coordinates
                            reverseGeocode(lat, lng);
                            
                            // Reset button
                            currentLocationBtn.innerHTML = '<i class="fas fa-location-arrow me-2"></i>Use Current Location';
                            currentLocationBtn.disabled = false;
                            
                            // Show success message with accuracy info
                            const accuracy = position.coords.accuracy ? Math.round(position.coords.accuracy) : 'Unknown';
                            showLocationMessage(`Location detected! Accuracy: ${accuracy}m. Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`, 'success');
                        },
                        function(error) {
                            // Reset button
                            currentLocationBtn.innerHTML = '<i class="fas fa-location-arrow me-2"></i>Use Current Location';
                            currentLocationBtn.disabled = false;
                            
                            let errorMessage = 'Unable to get your location. ';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage += 'Please allow location access in your browser.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage += 'Location information is unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage += 'Location request timed out.';
                                    break;
                                default:
                                    errorMessage += 'An unknown error occurred.';
                                    break;
                            }
                            
                            showLocationMessage(errorMessage, 'error');
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 30000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    showLocationMessage('Geolocation is not supported by this browser.', 'error');
                }
            });
        }
        
        function showLocationMessage(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : (type === 'info' ? 'alert-info' : 'alert-danger');
            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.style.maxWidth = '400px';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            const timeout = type === 'info' ? 7000 : 5000;
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, timeout);
        }

        function initMap() {
            console.log('Initializing map...');
            
            // Use existing coordinates or default to Philippines center
            const defaultLat = <?php echo e($siteVisit->latitude ?? 12.8797); ?>;
            const defaultLng = <?php echo e($siteVisit->longitude ?? 121.7740); ?>;
            const hasCoordinates = <?php echo e($siteVisit->latitude ? 'true' : 'false'); ?>;
            
            console.log('Coordinates:', { defaultLat, defaultLng, hasCoordinates });
            
            // Philippines bounds (southwest and northeast corners)
            const philippinesBounds = [
                [4.5, 116.0],  // Southwest corner (Mindanao south, western edge)
                [21.0, 127.0]  // Northeast corner (Luzon north, eastern edge)
            ];
            
            // Initialize Leaflet map with Philippines restrictions
            map = L.map('map', {
                center: [defaultLat, defaultLng],
                zoom: hasCoordinates ? 13 : 6,
                minZoom: 6,  // Prevent zooming out too far
                maxZoom: 18, // Allow detailed zoom
                maxBounds: philippinesBounds, // Restrict panning to Philippines
                maxBoundsViscosity: 1.0 // Make bounds solid (can't drag outside)
            });

            console.log('Map initialized');

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            console.log('Tiles added');

            // Add existing marker only if coordinates exist
            if (hasCoordinates) {
                addMarker(defaultLat, defaultLng);
                console.log('Marker added at existing coordinates');
            } else {
                console.log('No existing coordinates - showing Philippines overview');
            }

            // Add click listener to map
            map.on('click', function(e) {
                addMarker(e.latlng.lat, e.latlng.lng);
            });
        }

        function addMarker(lat, lng) {
            // Remove existing marker
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker
            marker = L.marker([lat, lng]).addTo(map);

            // Update form fields
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('coordinates').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }

        function reverseGeocode(lat, lng) {
            // Use Nominatim (OpenStreetMap) reverse geocoding service
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.querySelector('textarea[name="location_address"]').value = data.display_name;
                        
                        // Show a notification about the detected address
                        showLocationMessage(`Address detected: ${data.display_name}`, 'info');
                    }
                })
                .catch(error => {
                    console.log('Reverse geocoding failed:', error);
                    // Don't show error to user as this is optional
                });
        }

        // Allow re-clicking a selected Yes/No radio to unselect it (toggle off)
        function setupToggleableRadios() {
            const radios = document.querySelectorAll(
                '.physical-factors input[type="radio"], '
                + '.topography input[type="radio"], '
                + '.geotechnical-soils input[type="radio"], '
                + '.utilities input[type="radio"], '
                + '.immediate-surroundings input[type="radio"]'
            );
            radios.forEach((radio) => {
                radio.addEventListener('mousedown', function () {
                    this.dataset.waschecked = this.checked ? 'true' : 'false';
                });
                radio.addEventListener('click', function () {
                    if (this.dataset.waschecked === 'true') {
                        this.checked = false;
                        // Trigger change in case any listeners depend on it
                        this.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            });
        }

        // Link Existing User selector: auto-fill client fields and toggle required
        document.addEventListener('DOMContentLoaded', function () {
            const userSelect = document.getElementById('user_id');
            const nameInput = document.getElementById('client');
            const contactInput = document.getElementById('contact_number');
            const emailInput = document.getElementById('email');

            function toggleClientRequired() {
                const hasUser = !!userSelect && userSelect.value !== '';
                // Client name and email can be auto-filled from user, so they're optional when user is selected
                nameInput.required = !hasUser;
                emailInput.required = !hasUser;
                // Contact number is ALWAYS required, even when user is selected
                contactInput.required = true;
            }

            function fillFromSelectedUser() {
                const opt = userSelect.options[userSelect.selectedIndex];
                if (!opt || !opt.value) { toggleClientRequired(); return; }
                const n = opt.getAttribute('data-name') || '';
                const c = opt.getAttribute('data-contact') || '';
                const e = opt.getAttribute('data-email') || '';
                if (!nameInput.value) nameInput.value = n;
                if (!contactInput.value) contactInput.value = c;
                if (!emailInput.value) emailInput.value = e;
                toggleClientRequired();
            }

            if (userSelect) {
                userSelect.addEventListener('change', fillFromSelectedUser);
                // Initialize on load
                toggleClientRequired();
                if (userSelect.value) fillFromSelectedUser();
            }
            
            // Add form validation for edit form
            const form = document.querySelector('form[action*="site-visits"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // First, let browser's native HTML5 validation run
                    if (!form.checkValidity()) {
                        // Browser will show native validation messages
                        return;
                    }
                    
                    const lat = document.getElementById('latitude').value;
                    const lng = document.getElementById('longitude').value;
                    
                    console.log('Form submitting - Contact Number:', document.getElementById('contact_number').value);
                    
                    // Check location coordinates
                    if (!lat || !lng || lat === '' || lng === '') {
                        e.preventDefault();
                        alert('⚠️ LOCATION REQUIRED\n\nPlease click on the map to select a location before saving.\n\nThe latitude and longitude coordinates are required.');
                        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                    
                    // Check required fields (double-check after HTML5 validation)
                    const requiredFields = [
                        { id: 'client', name: 'Client Name', element: document.getElementById('client') },
                        { id: 'contact_number', name: 'Contact Number', element: document.getElementById('contact_number') },
                        { id: 'email', name: 'Email', element: document.getElementById('email') },
                        { id: 'location', name: 'Site Location', element: document.querySelector('input[name="location"]') },
                        { id: 'site_inspector', name: 'Site Inspector', element: document.querySelector('input[name="site_inspector"]') },
                        { id: 'visit_date', name: 'Visit Date', element: document.querySelector('input[name="visit_date"]') },
                        { id: 'status', name: 'Status', element: document.querySelector('select[name="status"]') }
                    ];
                    
                    const missingFields = [];
                    const emptyElements = [];
                    
                    for (const field of requiredFields) {
                        if (field.element && field.element.required && !field.element.value.trim()) {
                            missingFields.push(field.name);
                            emptyElements.push(field.element);
                            field.element.classList.add('is-invalid');
                            field.element.style.borderColor = '#dc3545';
                            field.element.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                        } else if (field.element) {
                            field.element.classList.remove('is-invalid');
                            field.element.style.borderColor = '';
                            field.element.style.boxShadow = '';
                        }
                    }
                    
                    if (missingFields.length > 0) {
                        e.preventDefault();
                        
                        const message = '⚠️ REQUIRED FIELDS MISSING\n\n' +
                                      'Please fill in the following required fields:\n\n' +
                                      missingFields.map((field, index) => `${index + 1}. ${field}`).join('\n') +
                                      '\n\nAll fields marked with (*) are required.';
                        
                        alert(message);
                        
                        if (emptyElements.length > 0) {
                            emptyElements[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                            setTimeout(() => {
                                emptyElements[0].focus();
                            }, 500);
                        }
                        
                        return false;
                    }
                    
                    // Show loading indicator
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                    }
                });
                
                // Add real-time validation feedback
                const allInputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                allInputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        if (this.required && !this.value.trim()) {
                            this.classList.add('is-invalid');
                            this.style.borderColor = '#dc3545';
                        } else {
                            this.classList.remove('is-invalid');
                            this.style.borderColor = '';
                            this.style.boxShadow = '';
                        }
                    });
                    
                    input.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('is-invalid');
                            this.style.borderColor = '';
                            this.style.boxShadow = '';
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visits/edit.blade.php ENDPATH**/ ?>