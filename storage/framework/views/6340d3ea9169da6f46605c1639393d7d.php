<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/client-data.css')); ?>?v=<?php echo e(rand(1000,9999) . time()); ?>" rel="stylesheet">
<style>
    /* Compact header with inline visit info */
    .client-data-header {
        background: linear-gradient(135deg, #198754 0%, #157347 100%);
        color: white;
        padding: 1rem;
        margin: -0.5rem -0.5rem 1rem -0.5rem;
        border-radius: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .client-data-header h2 {
        font-size: 1.1rem !important;
        margin: 0 0 0.5rem 0 !important;
        color: white !important;
    }
    
    .visit-info-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .visit-info-inline .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .visit-info-inline .info-label {
        font-weight: 600;
        opacity: 0.9;
    }
    
    .visit-info-inline .badge {
        font-size: 0.75rem;
        padding: 0.25em 0.6em;
    }
    
    .back-btn-header {
        background: rgba(255,255,255,0.2) !important;
        border: 1px solid rgba(255,255,255,0.3) !important;
        color: white !important;
        font-size: 0.75rem !important;
        padding: 0.3rem 0.6rem !important;
        border-radius: 0.375rem !important;
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }
    
    .back-btn-header:hover {
        background: rgba(255,255,255,0.3) !important;
        color: white !important;
    }
    
    .back-btn-header i {
        font-size: 0.75rem !important;
        margin-right: 0.25rem !important;
    }
    
    /* Mobile responsive header */
    @media (max-width: 768px) {
        .client-data-header {
            padding: 0.6rem;
        }
        
        .client-data-header h2 {
            font-size: 0.9rem !important;
            margin: 0 0 0.4rem 0 !important;
        }
        
        .header-top-row {
            align-items: flex-start !important;
        }
        
        .header-top-row .flex-grow-1 {
            flex-grow: 1 !important;
            max-width: calc(100% - 70px) !important;
        }
        
        .visit-info-inline {
            flex-direction: column;
            gap: 0.4rem;
            font-size: 0.7rem;
            margin-top: 0.4rem;
        }
        
        .visit-info-inline .info-item {
            gap: 0.3rem;
        }
        
        .visit-info-inline .badge {
            font-size: 0.65rem;
            padding: 0.2em 0.5em;
        }
        
        .back-btn-header {
            font-size: 0.6rem !important;
            padding: 0.2rem 0.35rem !important;
            border-radius: 0.25rem !important;
            min-width: auto !important;
            flex-shrink: 0 !important;
            width: auto !important;
            max-width: 60px !important;
        }
        
        .back-btn-header i {
            font-size: 0.6rem !important;
            margin-right: 0.2rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .client-data-header {
            padding: 0.5rem;
        }
        
        .client-data-header h2 {
            font-size: 0.85rem !important;
            margin: 0 0 0.3rem 0 !important;
        }
        
        .header-top-row .flex-grow-1 {
            max-width: calc(100% - 55px) !important;
        }
        
        .visit-info-inline {
            gap: 0.3rem;
            font-size: 0.65rem;
            margin-top: 0.3rem;
        }
        
        .visit-info-inline .info-item {
            gap: 0.25rem;
        }
        
        .visit-info-inline .info-item i {
            font-size: 0.7rem;
        }
        
        .visit-info-inline .badge {
            font-size: 0.6rem;
            padding: 0.15em 0.4em;
        }
        
        .back-btn-header {
            font-size: 0.55rem !important;
            padding: 0.18rem 0.3rem !important;
            max-width: 50px !important;
        }
        
        .back-btn-header i {
            font-size: 0.55rem !important;
            margin-right: 0.15rem !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid client-data-page" style="padding: 0.5rem;">
        <!-- Compact Header with Visit Info -->
        <div class="client-data-header">
            <div class="d-flex justify-content-between align-items-start header-top-row">
                <div class="flex-grow-1">
                    <h2>
                        <i class="fas fa-folder-open me-2"></i>
                        Client Data
                    </h2>
                    <div class="visit-info-inline">
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="info-label">Visit Date:</span>
                            <span><?php echo e(optional($siteVisit->visit_date)->format('M j, Y')); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-info-circle"></i>
                            <span class="info-label">Status:</span>
                            <span class="badge bg-<?php echo e($siteVisit->status_badge_color); ?>"><?php echo e(ucfirst(str_replace('_',' ', $siteVisit->status))); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="info-label">Location:</span>
                            <span><?php echo e($siteVisit->location_address ?? $siteVisit->location ?? '—'); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-upload"></i>
                            <span class="info-label">Uploads:</span>
                            <?php $isOpen = $isOpen ?? false; ?>
                            <?php if($isOpen): ?>
                                <span class="badge bg-success">Open</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Not Open</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <a href="<?php echo e(route('client-data.index')); ?>" class="btn back-btn-header">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" style="padding: 0.5rem; margin-bottom: 0.5rem;" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.5rem; font-size: 0.7rem;"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="padding: 0.5rem; margin-bottom: 0.5rem;" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.5rem; font-size: 0.7rem;"></button>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="padding: 0.5rem; margin-bottom: 0.5rem;" role="alert">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.5rem; font-size: 0.7rem;"></button>
            </div>
        <?php endif; ?>

        <!-- Client's Data Checklist -->
        <div class="card" style="margin-bottom: 0.5rem; height: auto;">
            <div class="card-header bg-white" style="padding: 0.4rem 0.5rem;">
                <h5 style="margin: 0; font-size: 0.9rem;"><i class="fas fa-file-upload me-2 text-success"></i>Client's Data Checklist</h5>
            </div>
            <div class="card-body" style="padding: 0.5rem; height: auto; max-height: none; overflow: visible;">
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
                    $canUpload = ($isAdmin || $isLinkedClient) && $isOpen;
                ?>

                <div>
                    <table class="client-data-table" style="width: 100%; border-collapse: collapse; background: white; font-size: 0.8rem;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="width: 250px; padding: 0.5rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; text-align: left; font-weight: 600;">Item</th>
                                <th style="padding: 0.3rem 0.5rem; font-size: 0.8rem; border: 1px solid #dee2e6; text-align: left; font-weight: 600;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Files</span>
                                        <?php if($canUpload): ?>
                                            <div id="deleteButtonsContainer">
                                                <button type="button" id="toggleDeleteMode" class="btn btn-sm btn-outline-danger" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                                <button type="button" id="cancelDeleteMode" class="btn btn-sm btn-secondary d-none" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                                <button type="button" id="deleteSelectedFiles" class="btn btn-sm btn-danger d-none" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                    <i class="fas fa-trash me-1"></i>Delete Selected
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </th>
                                <th style="width: 250px; padding: 0.5rem; font-size: 0.8rem; border: 1px solid #dee2e6; text-align: left; font-weight: 600;">Upload</th>
                                <th style="width: 150px; padding: 0.5rem; font-size: 0.8rem; border: 1px solid #dee2e6; text-align: left; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $clientDataItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $files = $cd[$key] ?? [];
                                    $st = $cdStatus[$key]['status'] ?? 'missing';
                                    $note = $cdStatus[$key]['note'] ?? null;
                                ?>
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="width: 200px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle; font-weight: 600;"><?php echo e($label); ?></td>
                                    <td style="padding: 0.1rem 0.5rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle;">
                                        <?php if(empty($files)): ?>
                                            <span class="text-muted" style="font-size: 0.75rem;">No files uploaded.</span>
                                        <?php else: ?>
                                            <ul class="mb-0 list-unstyled">
                                                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="d-flex align-items-center" style="margin-bottom: 0.2rem; font-size: 0.75rem;">
                                                        <input type="checkbox" class="file-checkbox me-2 d-none" 
                                                               data-item-key="<?php echo e($key); ?>" 
                                                               data-file-index="<?php echo e($index); ?>"
                                                               data-site-visit-id="<?php echo e($siteVisit->id); ?>">
                                                        <div class="flex-grow-1">
                                                            <?php
                                                                $fileName = $f['original_name'];
                                                                $displayName = strlen($fileName) > 20 ? substr($fileName, 0, 17) . '...' : $fileName;
                                                            ?>
                                                            <a href="<?php echo e(asset('storage/' . $f['path'])); ?>" 
                                                               target="_blank" 
                                                               class="file-link-mobile" 
                                                               data-full-name="<?php echo e($fileName); ?>"><?php echo e($displayName); ?></a>
                                                            <small class="text-muted" style="font-size: 0.7rem;">(<?php echo e($f['type']); ?>)</small>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 280px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle;">
                                        <?php if($canUpload): ?>
                                            <form action="<?php echo e(route('site-visits.client-data.upload', ['siteVisit' => $siteVisit->id, 'itemKey' => $key])); ?>" method="POST" enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="position-relative">
                                                        <input type="file" name="file" id="file-<?php echo e($key); ?>" class="d-none" required>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="document.getElementById('file-<?php echo e($key); ?>').click()" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                                            <i class="fas fa-paperclip me-1"></i><span class="file-name-display">Select File</span>
                                                        </button>
                                                    </div>
                                                    <button class="btn btn-success btn-sm w-100" type="submit" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"><i class="fas fa-upload me-1"></i>Upload</button>
                                                </div>
                                                <?php if($key === 'drone_map'): ?>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Allowed: pdf, jpg, jpeg, png, mp4, mov. Max 20MB.</small>
                                                <?php else: ?>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Allowed: pdf, jpg, jpeg, png. Max 20MB.</small>
                                                <?php endif; ?>
                                            </form>
                                            <script>
                                                document.getElementById('file-<?php echo e($key); ?>').addEventListener('change', function(e) {
                                                    const fileName = e.target.files[0]?.name || 'Select File';
                                                    const displayName = fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName;
                                                    this.closest('form').querySelector('.file-name-display').textContent = displayName;
                                                });
                                            </script>
                                        <?php else: ?>
                                            <?php if(!$isOpen): ?>
                                                <span class="text-muted" style="font-size: 0.75rem;">Uploads not open yet.</span>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size: 0.75rem;">No permission to upload.</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 120px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle;">
                                        <?php
                                            $badge = match($st) {
                                                'received' => 'success',
                                                'rejected' => 'danger',
                                                'submitted' => 'warning',
                                                'missing' => 'secondary',
                                                default => 'secondary'
                                            };
                                        ?>
                                        <span class="badge bg-<?php echo e($badge); ?>" style="font-size: 0.7rem;"><?php echo e(ucfirst($st)); ?></span>
                                        <?php if($note): ?>
                                            <small class="text-muted d-block" style="font-size: 0.65rem;">Note: <?php echo e($note); ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Proposal Checklist (read-only for clients) -->
        <div class="card" style="margin-bottom: 0.5rem; height: auto;">
            <div class="card-header bg-white" style="padding: 0.4rem 0.5rem;">
                <h5 style="margin: 0; font-size: 0.9rem;"><i class="fas fa-file-alt me-2 text-success"></i>Proposal Checklist</h5>
            </div>
            <div class="card-body" style="padding: 0.5rem; height: auto; max-height: none; overflow: visible;">
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
                ?>

                <div>
                    <table class="client-data-table" style="width: 100%; border-collapse: collapse; background: white; font-size: 0.8rem;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="width: 280px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; text-align: left; font-weight: 600;">Item</th>
                                <th style="padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; text-align: left; font-weight: 600;">Files</th>
                                <th style="width: 120px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; text-align: left; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $proposalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $files = $pp[$key] ?? [];
                                    $st = $ppStatus[$key]['status'] ?? 'pending';
                                    $badge = match($st) {
                                        'uploaded' => 'warning',
                                        'reviewed' => 'info',
                                        'approved' => 'success',
                                        'pending' => 'secondary',
                                        default => 'secondary'
                                    };
                                ?>
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="width: 280px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle; font-weight: 600;"><?php echo e($label); ?></td>
                                    <td style="padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle;">
                                        <?php if(empty($files)): ?>
                                            <span class="text-muted" style="font-size: 0.75rem;">No files uploaded.</span>
                                        <?php else: ?>
                                            <ul class="mb-0" style="padding-left: 1.2rem;">
                                                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li style="font-size: 0.75rem; margin-bottom: 0.2rem;">
                                                        <a href="<?php echo e(asset('storage/' . $f['path'])); ?>" target="_blank"><?php echo e($f['original_name']); ?></a>
                                                        <small class="text-muted" style="font-size: 0.7rem;">(<?php echo e($f['type']); ?>)</small>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </td>
                                    <td style="width: 120px; padding: 0.4rem; font-size: 0.8rem; border: 1px solid #dee2e6; white-space: normal; word-wrap: break-word; vertical-align: middle;">
                                        <span class="badge bg-<?php echo e($badge); ?>" style="font-size: 0.7rem;"><?php echo e(ucfirst($st)); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000); // 5 seconds
            });
            
            // No need for JavaScript fixes anymore - using custom table class
            
            const toggleDeleteBtn = document.getElementById('toggleDeleteMode');
            const cancelDeleteBtn = document.getElementById('cancelDeleteMode');
            const deleteSelectedBtn = document.getElementById('deleteSelectedFiles');
            const fileCheckboxes = document.querySelectorAll('.file-checkbox');

            if (!toggleDeleteBtn) return;

            // Toggle delete mode
            toggleDeleteBtn.addEventListener('click', function() {
                // Show checkboxes
                fileCheckboxes.forEach(cb => cb.classList.remove('d-none'));
                
                // Toggle buttons
                toggleDeleteBtn.classList.add('d-none');
                cancelDeleteBtn.classList.remove('d-none');
                deleteSelectedBtn.classList.remove('d-none');
            });

            // Cancel delete mode
            cancelDeleteBtn.addEventListener('click', function() {
                // Hide checkboxes and uncheck all
                fileCheckboxes.forEach(cb => {
                    cb.classList.add('d-none');
                    cb.checked = false;
                });
                
                // Toggle buttons
                toggleDeleteBtn.classList.remove('d-none');
                cancelDeleteBtn.classList.add('d-none');
                deleteSelectedBtn.classList.add('d-none');
            });

            // Delete selected files
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedFiles = Array.from(fileCheckboxes).filter(cb => cb.checked);
                
                if (selectedFiles.length === 0) {
                    AlertSystem.alert({
                        title: 'No Files Selected',
                        message: 'Please select at least one file to delete.',
                        type: 'warning'
                    });
                    return;
                }

                AlertSystem.confirm({
                    title: 'Delete Files?',
                    message: `Are you sure you want to delete ${selectedFiles.length} file(s)?`,
                    confirmText: 'Yes, Delete',
                    cancelText: 'Cancel',
                    onConfirm: function() {
                        performFileDelete(selectedFiles);
                    }
                });
            });
            
            function performFileDelete(selectedFiles) {

                // Create a form to submit deletions
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route("site-visits.client-data.bulk-delete", $siteVisit->id)); ?>';
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfInput);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Add selected files data
                selectedFiles.forEach((cb, index) => {
                    const itemKeyInput = document.createElement('input');
                    itemKeyInput.type = 'hidden';
                    itemKeyInput.name = `files[${index}][item_key]`;
                    itemKeyInput.value = cb.dataset.itemKey;
                    form.appendChild(itemKeyInput);

                    const fileIndexInput = document.createElement('input');
                    fileIndexInput.type = 'hidden';
                    fileIndexInput.name = `files[${index}][file_index]`;
                    fileIndexInput.value = cb.dataset.fileIndex;
                    form.appendChild(fileIndexInput);
                });

                document.body.appendChild(form);
                form.submit();
            }
            
            // Long-press to show full filename on mobile
            let longPressTimer;
            let tooltip;
            
            document.querySelectorAll('.file-link-mobile').forEach(link => {
                // Touch start - begin long press timer
                link.addEventListener('touchstart', function(e) {
                    const fullName = this.getAttribute('data-full-name');
                    const displayText = this.textContent;
                    
                    // Only show tooltip if filename is truncated
                    if (displayText.includes('...')) {
                        longPressTimer = setTimeout(() => {
                            // Create tooltip
                            tooltip = document.createElement('div');
                            tooltip.className = 'filename-tooltip';
                            tooltip.textContent = fullName;
                            tooltip.style.cssText = `
                                position: fixed;
                                background: rgba(0, 0, 0, 0.9);
                                color: white;
                                padding: 8px 12px;
                                border-radius: 6px;
                                font-size: 0.75rem;
                                z-index: 10000;
                                max-width: 80vw;
                                word-wrap: break-word;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                            `;
                            
                            // Position tooltip above the link
                            const rect = this.getBoundingClientRect();
                            tooltip.style.left = rect.left + 'px';
                            tooltip.style.top = (rect.top - 40) + 'px';
                            
                            document.body.appendChild(tooltip);
                            
                            // Vibrate if supported (haptic feedback)
                            if (navigator.vibrate) {
                                navigator.vibrate(50);
                            }
                        }, 500); // 500ms long press
                    }
                });
                
                // Touch end - clear timer and remove tooltip
                link.addEventListener('touchend', function(e) {
                    clearTimeout(longPressTimer);
                    if (tooltip) {
                        tooltip.remove();
                        tooltip = null;
                    }
                });
                
                // Touch cancel - clear timer and remove tooltip
                link.addEventListener('touchcancel', function(e) {
                    clearTimeout(longPressTimer);
                    if (tooltip) {
                        tooltip.remove();
                        tooltip = null;
                    }
                });
                
                // Touch move - cancel long press if finger moves
                link.addEventListener('touchmove', function(e) {
                    clearTimeout(longPressTimer);
                    if (tooltip) {
                        tooltip.remove();
                        tooltip = null;
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/client-data/show.blade.php ENDPATH**/ ?>