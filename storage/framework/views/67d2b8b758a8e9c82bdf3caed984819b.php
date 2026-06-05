<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<tr>
    <td style="text-align: left;"><?php echo e($index + 1); ?></td>
    <td style="text-align: left;"><?php echo e($user->first_name); ?></td>
    <td style="text-align: left;"><?php echo e($user->last_name); ?></td>
    <td style="text-align: left;"><?php echo e($user->email); ?></td>
    <td style="text-align: left;"><?php echo e($user->contact_number ?? 'N/A'); ?></td>
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
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<tr>
    <td colspan="7" class="text-center py-4">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <p class="text-muted">No users found</p>
    </td>
</tr>
<?php endif; ?>
<?php /**PATH C:\CODING\my_Inventory\resources\views/admin/users/partials/users-table-rows.blade.php ENDPATH**/ ?>