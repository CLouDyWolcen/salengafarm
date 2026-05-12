<?php if($siteVisits->count() > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Location</th>
                    <th>Visit Date</th>
                    <th>Status</th>
                    <th>Inspector</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $siteVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($visit->client); ?></strong><br>
                        <small class="text-muted"><?php echo e($visit->email); ?></small>
                    </td>
                    <td>
                        <i class="fas fa-map-marker-alt text-success me-1"></i>
                        <?php echo e(\Illuminate\Support\Str::limit($visit->location_address ?? $visit->location ?? 'N/A', 30)); ?>

                    </td>
                    <td><?php echo e($visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A'); ?></td>
                    <td>
                        <?php if($visit->status === 'completed'): ?>
                            <span class="badge bg-success">Completed</span>
                        <?php elseif($visit->status === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php elseif($visit->status === 'follow_up'): ?>
                            <span class="badge bg-info">Follow Up</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?php echo e(ucfirst($visit->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($visit->site_inspector ?? 'N/A'); ?></td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="<?php echo e(route('site-visits.show', $visit)); ?>" 
                               class="btn btn-outline-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('site-visits.edit', $visit)); ?>" 
                               class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm delete-visit" 
                                    data-visit-id="<?php echo e($visit->id); ?>"
                                    data-client="<?php echo e($visit->client); ?>"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="fas fa-map-marked-alt text-muted" style="font-size: 3rem;"></i>
        <h4 class="mt-3 text-muted"><?php echo e($emptyMessage ?? 'No site visits found'); ?></h4>
        <?php if($emptyMessage === 'No site visits yet'): ?>
            <p class="text-muted">Start by adding your first site visit!</p>
            <a href="<?php echo e(route('site-visits.create')); ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add Site Visit
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visits/partials/visits-table.blade.php ENDPATH**/ ?>