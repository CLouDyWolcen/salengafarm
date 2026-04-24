<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/client-data.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-3 client-data-page">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-folder-open me-2 text-success"></i>
                Client Data
            </h2>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
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

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Visit Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Uploads Open</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $siteVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $isOpen = ($visit->client_data_open ?? false) || ($visit->status === 'completed');
                                    $badge = $visit->status_badge_color;
                                ?>
                                <tr>
                                    <td><?php echo e(optional($visit->visit_date)->format('M j, Y')); ?></td>
                                    <td><?php echo e($visit->location ?? $visit->location_address ?? '—'); ?></td>
                                    <td><span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst(str_replace('_',' ', $visit->status))); ?></span></td>
                                    <td>
                                        <?php if($isOpen): ?>
                                            <span class="badge bg-success">Open</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Closed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('client-data.show', $visit)); ?>" class="btn btn-sm btn-success client-data-open-btn">
                                            <i class="fas fa-eye me-1"></i>Open
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">No client data available yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/client-data/index.blade.php ENDPATH**/ ?>