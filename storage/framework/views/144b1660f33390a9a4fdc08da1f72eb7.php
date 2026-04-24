<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Site Visit Details - Plant Inventory</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .info-section {
            background: #f8f9fa;
            border-left: 4px solid #198754;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
        }
        .info-section h5 {
            color: #198754;
            margin-bottom: 1rem;
        }
        #map {
            height: 400px;
            border-radius: 0.375rem;
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
        .media-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .media-item {
            position: relative;
            border-radius: 0.375rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .media-item img, .media-item video {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .media-item .media-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .checklist-item {
            background: #e9ecef;
            padding: 0.5rem;
            margin: 0.25rem 0;
            border-radius: 0.25rem;
            display: inline-block;
        }
        .info-row {
            margin-bottom: 0.75rem;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        /* Responsive layout to avoid content going off-screen */
        .main-content {
            margin-left: 240px;
            padding: 1rem 2rem;
            max-width: calc(100vw - 260px);
            overflow-x: hidden;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                max-width: 100vw;
                padding: 1rem;
            }
        }
        /* Constrain tables and wrap long filenames nicely */
        .info-section .table {
            table-layout: auto;
        }
        .info-section th, .info-section td {
            word-break: break-word;
            vertical-align: middle;
        }
        .table-responsive { overflow-x: auto; }
        .status-select { min-width: 140px; }
        .note-input { min-width: 220px; }
        .upload-group .form-control[type="file"] { min-width: 200px; }
        .table > :not(caption) > * > * { padding: .5rem .65rem; }
        /* Sticky table headers for long lists */
        .table thead th { position: sticky; top: 0; background: #fff; z-index: 2; }
        /* Slightly tighter info section spacing */
        .info-section { padding: .9rem; }
    </style>
</head>
<body class="bg-light">
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-content">
            <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Desktop Layout -->
                <div class="d-none d-md-flex justify-content-between align-items-center mb-2">
                    <h2>
                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                        Site Visit Details
                        <span class="badge bg-<?php echo e($siteVisit->status_badge_color); ?> status-badge ms-2">
                            <?php echo e(ucfirst(str_replace('_', ' ', $siteVisit->status))); ?>

                        </span>
                    </h2>
                    <div>
                        <?php if(auth()->check() && auth()->user()->hasAdminAccess() && auth()->user()->role !== 'super_admin'): ?>
                            <form action="<?php echo e(route('site-visits.update-status', $siteVisit)); ?>" method="POST" class="d-inline-flex align-items-center me-2">
                                <?php echo csrf_field(); ?>
                                <select name="status" class="form-select form-select-sm" style="width:auto; margin-right: 1.5rem;">
                                    <option value="pending" <?php echo e($siteVisit->status==='pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="completed" <?php echo e($siteVisit->status==='completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="follow_up" <?php echo e($siteVisit->status==='follow_up' ? 'selected' : ''); ?>>Follow-up</option>
                                </select>
                                <div class="form-check form-check-sm" style="margin-right: 1.5rem;">
                                    <input class="form-check-input" type="checkbox" id="quick_cdo" name="client_data_open" value="1" <?php echo e($siteVisit->client_data_open ? 'checked' : ''); ?>>
                                    <label class="form-check-label small" for="quick_cdo">Open Client Data</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        <?php endif; ?>
                        <?php if(auth()->user()->role !== 'super_admin'): ?>
                        <a href="<?php echo e(route('site-visits.edit', $siteVisit)); ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Layout -->
                <div class="d-md-none mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 style="font-size: 1.1rem; margin: 0;">
                            <i class="fas fa-map-marker-alt me-2 text-success"></i>
                            Site Visit Details
                            <span class="badge bg-<?php echo e($siteVisit->status_badge_color); ?> d-block mt-2" style="font-size: 0.75rem; width: fit-content;">
                                <?php echo e(ucfirst(str_replace('_', ' ', $siteVisit->status))); ?>

                            </span>
                        </h2>
                        <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                            <i class="fas fa-arrow-left" style="font-size: 0.7rem;"></i> Back to List
                        </a>
                    </div>
                    
                    <?php if(auth()->check() && auth()->user()->hasAdminAccess() && auth()->user()->role !== 'super_admin'): ?>
                    <form action="<?php echo e(route('site-visits.update-status', $siteVisit)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100">Update</button>
                            </div>
                            <div class="col-6">
                                <?php if(auth()->user()->role !== 'super_admin'): ?>
                                <a href="<?php echo e(route('site-visits.edit', $siteVisit)); ?>" class="btn btn-warning w-100">Edit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" <?php echo e($siteVisit->status==='pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="completed" <?php echo e($siteVisit->status==='completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="follow_up" <?php echo e($siteVisit->status==='follow_up' ? 'selected' : ''); ?>>Follow-up</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <div class="form-check d-flex align-items-center h-100">
                                    <input class="form-check-input me-2" type="checkbox" id="quick_cdo_mobile" name="client_data_open" value="1" <?php echo e($siteVisit->client_data_open ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="quick_cdo_mobile" style="font-size: 0.85rem;">Open Client Data</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php else: ?>
                        <?php if(auth()->user()->role !== 'super_admin'): ?>
                        <a href="<?php echo e(route('site-visits.edit', $siteVisit)); ?>" class="btn btn-warning w-100">Edit</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                </div>

                <!-- Location Map -->
                <div class="info-section">
                    <h5><i class="fas fa-map-pin me-2"></i>Location</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div id="map"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Coordinates:</div>
                                <div><?php echo e($siteVisit->latitude); ?>, <?php echo e($siteVisit->longitude); ?></div>
                            </div>
                            <?php if($siteVisit->location_address): ?>
                                <div class="info-row">
                                    <div class="info-label">Address:</div>
                                    <div><?php echo e($siteVisit->location_address); ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="info-row">
                                <div class="info-label">Site Location:</div>
                                <div><?php echo e($siteVisit->location); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client & Project Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5><i class="fas fa-user me-2"></i>Client Information</h5>
                            <div class="info-row">
                                <div class="info-label">Client Name:</div>
                                <div><?php echo e($siteVisit->client); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Contact Number:</div>
                                <div><?php echo e($siteVisit->contact_number); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email:</div>
                                <div><?php echo e($siteVisit->email); ?></div>
                            </div>
                            <?php if($siteVisit->job_no): ?>
                                <div class="info-row">
                                    <div class="info-label">Job Number:</div>
                                    <div><?php echo e($siteVisit->job_no); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5><i class="fas fa-project-diagram me-2"></i>Project Information</h5>
                            <?php if($siteVisit->project_code): ?>
                                <div class="info-row">
                                    <div class="info-label">Project Code:</div>
                                    <div><?php echo e($siteVisit->project_code); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if($siteVisit->project_no): ?>
                                <div class="info-row">
                                    <div class="info-label">Project Number:</div>
                                    <div><?php echo e($siteVisit->project_no); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if($siteVisit->landscape_area): ?>
                                <div class="info-row">
                                    <div class="info-label">Landscape Area:</div>
                                    <div><?php echo e($siteVisit->landscape_area); ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="info-row">
                                <div class="info-label">Site Inspector:</div>
                                <div><?php echo e($siteVisit->site_inspector); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Visit Date:</div>
                                <div><?php echo e($siteVisit->visit_date->format('F j, Y')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scope of Work (Static Text) -->
                <div class="info-section">
                    <h5><i class="fas fa-tasks me-2"></i>Scope of Work</h5>
                    <?php echo $__env->make('site-visits._scope_of_work_text', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <?php if($siteVisit->physical_factors): ?>
                    <div class="info-section">
                        <h4 class="text-center mb-2">Physical Factors</h4>
                        <h5><i class="fas fa-cloud-sun me-2"></i>Climate Factors</h5>
                        <div>
                            <?php $hasYes_pf = false; ?>
                            <?php $__currentLoopData = $siteVisit->physical_factors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_pf = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_pf): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>
                            <?php
                                $otherRemarks_pf = [];
                                foreach(($siteVisit->physical_factors ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_pf[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_pf) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_pf; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->physical_factors) && !empty($siteVisit->physical_factors['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->physical_factors['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->topography): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-mountain me-2"></i>Topography</h5>
                        <div>
                            <?php $hasYes_top = false; ?>
                            <?php $__currentLoopData = $siteVisit->topography; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_top = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_top): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>
                            <?php
                                $otherRemarks_top = [];
                                foreach(($siteVisit->topography ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_top[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_top) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->topography) && !empty($siteVisit->topography['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->topography['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->geotechnical_soils): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-layer-group me-2"></i>Geotechnical & Soils</h5>
                        <div>
                            <?php $hasYes_gs = false; ?>
                            <?php $__currentLoopData = $siteVisit->geotechnical_soils; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_gs = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_gs): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>
                            <?php
                                $otherRemarks_gs = [];
                                foreach(($siteVisit->geotechnical_soils ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_gs[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_gs) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_gs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->geotechnical_soils) && !empty($siteVisit->geotechnical_soils['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->geotechnical_soils['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->utilities): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-plug me-2"></i>Utilities</h5>
                        <div>
                            <?php $hasYes_ut = false; ?>
                            <?php $__currentLoopData = $siteVisit->utilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_ut = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_ut): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>
                            <?php
                                $otherRemarks_ut = [];
                                foreach(($siteVisit->utilities ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_ut[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_ut) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_ut; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->utilities) && !empty($siteVisit->utilities['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->utilities['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->immediate_surroundings): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-eye me-2"></i>Immediate Surroundings</h5>
                        <div>
                            <?php $hasYes_is = false; ?>
                            <?php $__currentLoopData = $siteVisit->immediate_surroundings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_is = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_is): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>
                            <?php
                                $otherRemarks_is = [];
                                foreach(($siteVisit->immediate_surroundings ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_is[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_is) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_is; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->immediate_surroundings) && !empty($siteVisit->immediate_surroundings['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->immediate_surroundings['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->additional_services): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-people-carry-box me-2"></i>Additional Services</h5>
                        <div>
                            <?php $hasYes_as = false; ?>
                            <?php $__currentLoopData = $siteVisit->additional_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                ?>
                                <?php if($key !== 'notes' && $val === 'yes'): ?>
                                    <?php $hasYes_as = true; ?>
                                    <span class="checklist-item"><?php echo e($label); ?><?php if($remarks): ?> - <?php echo e($remarks); ?><?php endif; ?></span>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$hasYes_as): ?>
                                <span class="text-muted">No items marked Yes.</span>
                            <?php endif; ?>

                            <?php
                                $otherRemarks_as = [];
                                foreach(($siteVisit->additional_services ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_as[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            ?>
                            <?php if(count($otherRemarks_as) > 0): ?>
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $otherRemarks_as; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $or): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><small><?php echo e($or['label']); ?> - <?php echo e($or['remarks']); ?></small></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(is_array($siteVisit->additional_services) && !empty($siteVisit->additional_services['notes'])): ?>
                            <div class="mt-2"><small class="text-muted">Notes: <?php echo e($siteVisit->additional_services['notes']); ?></small></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($siteVisit->tools_checklist): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-toolbox me-2"></i>Tools</h5>
                        <?php
                            $tl = $siteVisit->tools_checklist ?? [];
                            $categories = [
                                'safety' => 'Safety',
                                'documentation' => 'Documentation',
                                'drawing' => 'Drawing',
                            ];
                            $hasAnyTool = false;
                        ?>
                        <div class="row">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $catLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $checked = [];
                                    foreach(($tl[$catKey] ?? []) as $toolKey => $val) {
                                        if($val){
                                            $checked[] = ucwords(str_replace('_', ' ', $toolKey));
                                        }
                                    }
                                ?>
                                <?php if(count($checked) > 0): ?>
                                    <?php $hasAnyTool = true; ?>
                                    <div class="col-md-4 mb-2">
                                        <strong><?php echo e($catLabel); ?></strong>
                                        <ul class="mb-0">
                                            <?php $__currentLoopData = $checked; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($t); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if(!$hasAnyTool): ?>
                            <span class="text-muted">No tools checked.</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Media Files -->
                <?php if($siteVisit->media_files && count($siteVisit->media_files) > 0): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-camera me-2"></i>Media Files</h5>
                        <div class="media-gallery">
                            <?php $__currentLoopData = $siteVisit->media_files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="media-item">
                                    <?php if(str_starts_with($file['type'], 'image/')): ?>
                                        <img src="<?php echo e(asset('storage/' . $file['path'])); ?>" alt="<?php echo e($file['original_name']); ?>" 
                                             class="img-fluid" data-bs-toggle="modal" data-bs-target="#mediaModal<?php echo e($index); ?>">
                                    <?php elseif(str_starts_with($file['type'], 'video/')): ?>
                                        <video controls class="w-100">
                                            <source src="<?php echo e(asset('storage/' . $file['path'])); ?>" type="<?php echo e($file['type']); ?>">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php endif; ?>
                                    <div class="media-overlay">
                                        <?php echo e($file['original_name']); ?>

                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <?php if(str_starts_with($file['type'], 'image/')): ?>
                                    <div class="modal fade" id="mediaModal<?php echo e($index); ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?php echo e($file['original_name']); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo e(asset('storage/' . $file['path'])); ?>" alt="<?php echo e($file['original_name']); ?>" 
                                                         class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notes -->
                <?php if($siteVisit->notes): ?>
                    <div class="info-section">
                        <h5><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
                        <div class="bg-white p-3 border-start border-success border-3">
                            <?php echo nl2br(e($siteVisit->notes)); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- Client's Data Checklist -->
                <div class="info-section">
                    <h5><i class="fas fa-folder-open me-2"></i>Client's Data Checklist</h5>
                    <?php
                        $clientDataItems = [
                            'land_title' => 'Land Title',
                            'sketch_plan' => 'Sketch Plan',
                            'topogrophy' => 'Topography',
                            'tree_map' => 'Tree Map',
                            'site_development_plan_sdp' => 'Site Development Plan (SDP)',
                            'master_development_plant_mdp' => 'Master Development Plan (MDP)',
                            'drone_map' => 'Drone Map',
                        ];
                        $cd = $siteVisit->client_data_checklist ?? [];
                        $cdStatus = $siteVisit->client_data_statuses ?? [];
                        $isAdmin = auth()->check() && auth()->user()->hasAdminAccess();
                        $isLinkedClient = auth()->check() && $siteVisit->user_id === auth()->id();
                        $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
                        $canUploadClientData = $isAdmin || ($isLinkedClient && $isOpen);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle bg-white">
                            <thead>
                                <tr>
                                    <th style="width: 28%">Item</th>
                                    <th>Files</th>
                                    <th style="width: 22%">Upload</th>
                                    <th style="width: 18%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $clientDataItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $files = $cd[$key] ?? [];
                                        $st = $cdStatus[$key]['status'] ?? 'missing';
                                        $note = $cdStatus[$key]['note'] ?? null;
                                    ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo e($label); ?></td>
                                        <td>
                                            <?php if(empty($files)): ?>
                                                <span class="text-muted">No files uploaded.</span>
                                            <?php else: ?>
                                                <ul class="mb-0">
                                                    <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <a href="<?php echo e(asset('storage/' . $f['path'])); ?>" target="_blank"><?php echo e($f['original_name']); ?></a>
                                                            <small class="text-muted">(<?php echo e($f['type']); ?>)</small>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($canUploadClientData && auth()->user()->role !== 'super_admin'): ?>
                                                <form action="<?php echo e(route('site-visits.client-data.upload', ['siteVisit' => $siteVisit->id, 'itemKey' => $key])); ?>" method="POST" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="input-group input-group-sm upload-group">
                                                        <input type="file" name="file" class="form-control" required>
                                                        <button class="btn btn-success" type="submit"><i class="fas fa-upload me-1"></i>Upload</button>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">No permission to upload.</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                <?php
                                                    $badge = match($st) {
                                                        'received' => 'success',
                                                        'rejected' => 'danger',
                                                        'submitted' => 'warning',
                                                        'missing' => 'secondary',
                                                        default => 'secondary'
                                                    };
                                                ?>
                                                <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($st)); ?></span>
                                                <?php if($note): ?>
                                                    <small class="text-muted d-block">Note: <?php echo e($note); ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($isAdmin && auth()->user()->role !== 'super_admin'): ?>
                                                <form action="<?php echo e(route('site-visits.client-data.status', ['siteVisit' => $siteVisit->id, 'itemKey' => $key])); ?>" method="POST" class="row g-2 align-items-center">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="col-auto">
                                                        <select name="status" class="form-select form-select-sm status-select" required>
                                                            <option value="received" <?php echo e($st==='received'?'selected':''); ?>>✔ Received</option>
                                                            <option value="rejected" <?php echo e($st==='rejected'?'selected':''); ?>>✘ Rejected</option>
                                                            <option value="missing" <?php echo e($st==='missing'?'selected':''); ?>>Missing</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" name="note" class="form-control form-control-sm note-input" placeholder="Add note (optional)" value="<?php echo e($note); ?>">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-sm btn-outline-primary" type="submit">Update</button>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Proposal Checklist -->
                <div class="info-section">
                    <h5><i class="fas fa-file-alt me-2"></i>Proposal Checklist</h5>
                    <?php
                        $proposalItems = [
                            'concept_board' => 'Concept Board',
                            'design_service_agreement' => 'Design Service Agreement',
                            'build_service_agreement' => 'Build Service Agreement',
                            'design_quotation' => 'Design Quotation',
                            'build_quotation_rough_estimate' => 'Build Quotation: Rough Estimate',
                            'supervision_quotation' => 'Supervision Quotation',
                            'bill_of_materials_boq' => 'Bill of Materials (BOQ)',
                        ];
                        $pp = $siteVisit->proposal_checklist ?? [];
                        $ppStatus = $siteVisit->proposal_item_statuses ?? [];
                        $approval = $siteVisit->proposal_approval ?? null;
                        $isAdmin = auth()->check() && auth()->user()->hasAdminAccess();
                        $isLinkedClient = auth()->check() && $siteVisit->user_id === auth()->id();
                    ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle bg-white">
                            <thead>
                                <tr>
                                    <th style="width: 28%">Item</th>
                                    <th>Files</th>
                                    <th style="width: 22%">Upload (Admin)</th>
                                    <th style="width: 18%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $proposalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $files = $pp[$key] ?? [];
                                        $st = $ppStatus[$key]['status'] ?? 'pending';
                                    ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo e($label); ?></td>
                                        <td>
                                            <?php if(empty($files)): ?>
                                                <span class="text-muted">No files uploaded.</span>
                                            <?php else: ?>
                                                <ul class="mb-0">
                                                    <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <a href="<?php echo e(asset('storage/' . $f['path'])); ?>" target="_blank"><?php echo e($f['original_name']); ?></a>
                                                            <small class="text-muted">(<?php echo e($f['type']); ?>)</small>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($isAdmin && auth()->user()->role !== 'super_admin'): ?>
                                                <form action="<?php echo e(route('site-visits.proposal.upload', ['siteVisit' => $siteVisit->id, 'itemKey' => $key])); ?>" method="POST" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="input-group input-group-sm upload-group">
                                                        <input type="file" name="file" class="form-control" required>
                                                        <button class="btn btn-success" type="submit"><i class="fas fa-upload me-1"></i>Upload</button>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">Admin upload only.</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $badge = match($st) {
                                                    'uploaded' => 'warning',
                                                    'reviewed' => 'info',
                                                    'approved' => 'success',
                                                    'pending' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($st)); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>Client Approval:</strong>
                        <?php if($approval): ?>
                            <?php $ab = ($approval['status'] === 'approved') ? 'success' : 'danger'; ?>
                            <span class="badge bg-<?php echo e($ab); ?> text-uppercase"><?php echo e(str_replace('_',' ', $approval['status'])); ?></span>
                            <?php if(!empty($approval['comment'])): ?>
                                <small class="text-muted ms-2">“<?php echo e($approval['comment']); ?>”</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>

                        <?php if($isLinkedClient): ?>
                            <form class="mt-2" action="<?php echo e(route('site-visits.proposal.approval', $siteVisit)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Decision</label>
                                        <select name="decision" class="form-select form-select-sm" required>
                                            <option value="approved">Approve</option>
                                            <option value="changes_requested">Request changes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Comment (optional)</label>
                                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add a comment">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary w-100" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <a href="<?php echo e(route('site-visits.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                    <?php if(auth()->user()->role !== 'super_admin'): ?>
                    <div>
                        <a href="<?php echo e(route('site-visits.edit', $siteVisit)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Visit
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Delete Visit
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script>
        function initMap() {
            const lat = <?php echo e($siteVisit->latitude); ?>;
            const lng = <?php echo e($siteVisit->longitude); ?>;
            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            const popupHtml = `
                <div>
                    <h6><strong><?php echo e($siteVisit->client); ?></strong></h6>
                    <p><strong>Location:</strong> <?php echo e($siteVisit->location); ?></p>
                    <p><strong>Visit Date:</strong> <?php echo e($siteVisit->visit_date->format('M j, Y')); ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-<?php echo e($siteVisit->status_badge_color); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $siteVisit->status))); ?></span></p>
                </div>
            `;
            marker.bindPopup(popupHtml).openPopup();
        }

        function confirmDelete() {
            AlertSystem.confirm({
                title: 'Delete Site Visit?',
                message: 'Are you sure you want to delete this site visit? This action cannot be undone.',
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    fetch('<?php echo e(route("site-visits.destroy", $siteVisit)); ?>', {
                        method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        AlertSystem.toast({
                            message: 'Site visit deleted successfully',
                            type: 'success'
                        });
                        setTimeout(() => {
                            window.location.href = '<?php echo e(route("site-visits.index")); ?>';
                        }, 1000);
                    } else {
                        AlertSystem.alert({
                            title: 'Error',
                            message: 'Error deleting site visit: ' + data.message,
                            type: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    AlertSystem.alert({
                        title: 'Error',
                        message: 'An error occurred while deleting the site visit',
                        type: 'error'
                    });
                });
                }
            });
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', initMap);
    </script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visits/show.blade.php ENDPATH**/ ?>