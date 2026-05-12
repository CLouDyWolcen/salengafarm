<tr class="request-row <?php echo e($request->status); ?>" data-request-id="<?php echo e($request->id); ?>">
    <td class="align-middle">
        <strong><?php echo e($request->user->first_name ?? ''); ?> <?php echo e($request->user->last_name ?? ''); ?></strong>
        <?php if($request->user->contact_number): ?>
            <br><small class="text-muted"><i class="fas fa-phone me-1"></i><?php echo e($request->user->contact_number); ?></small>
        <?php endif; ?>
    </td>
    <td class="align-middle">
        <small><?php echo e($request->user->email); ?></small>
    </td>
    <td class="align-middle">
        <?php echo e($request->preferred_date->format('M d, Y')); ?>

        <?php if($request->preferred_time): ?>
            <br><small class="text-muted"><?php echo e(date('g:i A', strtotime($request->preferred_time))); ?></small>
        <?php endif; ?>
    </td>
    <td class="align-middle">
        <small><?php echo e(\Illuminate\Support\Str::limit($request->property_address, 40)); ?></small>
    </td>
    <td class="text-center align-middle">
        <span class="badge bg-<?php echo e($request->status_badge_color); ?>">
            <?php echo e(ucfirst($request->status)); ?>

        </span>
    </td>
    <td class="text-center align-middle">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-success expand-btn" onclick="toggleDetails(<?php echo e($request->id); ?>)" title="View Details">
                <i class="fas fa-chevron-down" id="icon-<?php echo e($request->id); ?>"></i>
            </button>
            <?php if($request->status === 'pending'): ?>
                <button type="button" class="btn btn-sm btn-success" onclick="approveRequest(<?php echo e($request->id); ?>)" title="Approve">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="rejectRequest(<?php echo e($request->id); ?>)" title="Reject">
                    <i class="fas fa-times"></i>
                </button>
            <?php elseif($request->status === 'approved' && $request->site_visit_id): ?>
                <a href="<?php echo e(route('site-visits.edit', $request->site_visit_id)); ?>" class="btn btn-sm btn-primary" title="View Site Visit">
                    <i class="fas fa-map-marker-alt"></i>
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRequest(<?php echo e($request->id); ?>)" title="Delete Request">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
<tr class="details-row" id="details-<?php echo e($request->id); ?>" style="display: none;">
    <td colspan="6" class="p-0">
        <div class="details-content p-3 bg-light">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-success mb-3"><i class="fas fa-info-circle me-2"></i>Request Details</h6>
                    
                    <div class="row mb-3">
                        <?php if($request->property_size): ?>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-ruler-combined me-2 text-success"></i>Property Size:</strong>
                            <div class="ms-4"><?php echo e($request->property_size); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($request->current_condition): ?>
                        <div class="col-md-6 mb-2">
                            <strong><i class="fas fa-info-circle me-2 text-success"></i>Current Condition:</strong>
                            <div class="ms-4"><?php echo e(ucwords(str_replace('_', ' ', $request->current_condition))); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-clipboard-list me-2 text-success"></i>Project Description:</strong>
                        <p class="ms-4 mb-0 mt-1"><?php echo e($request->project_description); ?></p>
                    </div>

                    <?php if($request->special_requirements): ?>
                    <div class="mb-3">
                        <strong><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Special Requirements:</strong>
                        <p class="ms-4 mb-0 mt-1"><?php echo e($request->special_requirements); ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>Requested on <?php echo e($request->created_at->format('M d, Y g:i A')); ?>

                        </small>
                    </div>

                    <?php if($request->status !== 'pending'): ?>
                        <div class="mt-3 p-3 bg-white rounded border">
                            <?php if($request->status === 'approved' && $request->admin_notes): ?>
                                <strong class="text-success"><i class="fas fa-check-circle me-2"></i>Admin Notes:</strong>
                                <p class="mb-0 mt-1"><?php echo e($request->admin_notes); ?></p>
                            <?php elseif($request->status === 'rejected' && $request->rejection_reason): ?>
                                <strong class="text-danger"><i class="fas fa-times-circle me-2"></i>Rejection Reason:</strong>
                                <p class="mb-0 mt-1"><?php echo e($request->rejection_reason); ?></p>
                            <?php endif; ?>
                            <small class="text-muted d-block mt-2">
                                Reviewed by <?php echo e($request->reviewer->first_name ?? ''); ?> <?php echo e($request->reviewer->last_name ?? ''); ?> 
                                on <?php echo e($request->reviewed_at->format('M d, Y g:i A')); ?>

                            </small>
                            <?php if($request->status === 'approved' && $request->site_visit_id): ?>
                                <div class="mt-3">
                                    <a href="<?php echo e(route('site-visits.edit', $request->site_visit_id)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i>View Site Visit
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <?php if($request->photos && count($request->photos) > 0): ?>
                        <h6 class="text-success mb-3"><i class="fas fa-camera me-2"></i>Property Photos</h6>
                        <div class="d-flex flex-wrap">
                            <?php $__currentLoopData = $request->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(Storage::url($photo)); ?>" 
                                     alt="Property Photo" 
                                     class="photo-thumbnail mb-2 me-2"
                                     onclick="viewPhoto('<?php echo e(Storage::url($photo)); ?>')">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="fas fa-image me-2"></i>No photos uploaded
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </td>
</tr>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visit-requests/partials/request-card.blade.php ENDPATH**/ ?>