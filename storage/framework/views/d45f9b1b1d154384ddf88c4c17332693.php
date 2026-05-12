

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/client-data.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
<style>
    .form-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .form-section h5 {
        color: #2d5530;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #28a745;
    }
    .photo-preview {
        position: relative;
        display: inline-block;
        margin: 0.5rem;
    }
    .photo-preview img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }
    .photo-preview .remove-photo {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
    }
    .photo-preview .remove-photo:hover {
        background: #c82333;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-plus me-2 text-success"></i>
            Request Site Visit
        </h2>
        <a href="<?php echo e(route('client-data.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Site Data
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('site-visit-requests.store')); ?>" method="POST" enctype="multipart/form-data" id="requestForm">
        <?php echo csrf_field(); ?>

        <!-- Visit Details Section -->
        <div class="form-section">
            <h5><i class="fas fa-calendar-alt me-2"></i>Visit Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control <?php $__errorArgs = ['preferred_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="preferred_date" name="preferred_date" value="<?php echo e(old('preferred_date')); ?>" 
                           min="<?php echo e(date('Y-m-d')); ?>" required>
                    <?php $__errorArgs = ['preferred_date'];
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
                    <label for="preferred_time" class="form-label">Preferred Time</label>
                    <input type="time" class="form-control <?php $__errorArgs = ['preferred_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="preferred_time" name="preferred_time" value="<?php echo e(old('preferred_time')); ?>">
                    <?php $__errorArgs = ['preferred_time'];
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
            </div>
        </div>

        <!-- Property Information Section -->
        <div class="form-section">
            <h5><i class="fas fa-map-marker-alt me-2"></i>Property Information</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="property_address" class="form-label">Property Address <span class="text-danger">*</span></label>
                    <textarea class="form-control <?php $__errorArgs = ['property_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              id="property_address" name="property_address" rows="2" 
                              required><?php echo e(old('property_address')); ?></textarea>
                    <?php $__errorArgs = ['property_address'];
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
                    <label for="property_size" class="form-label">Property Size (e.g., 500 sqm, 1 hectare)</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['property_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           id="property_size" name="property_size" value="<?php echo e(old('property_size')); ?>" 
                           placeholder="e.g., 500 sqm">
                    <?php $__errorArgs = ['property_size'];
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
                    <label for="current_condition" class="form-label">Current Condition</label>
                    <select class="form-select <?php $__errorArgs = ['current_condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                            id="current_condition" name="current_condition">
                        <option value="">Select condition...</option>
                        <option value="bare_land" <?php echo e(old('current_condition') == 'bare_land' ? 'selected' : ''); ?>>Bare Land</option>
                        <option value="with_existing_plants" <?php echo e(old('current_condition') == 'with_existing_plants' ? 'selected' : ''); ?>>With Existing Plants</option>
                        <option value="needs_clearing" <?php echo e(old('current_condition') == 'needs_clearing' ? 'selected' : ''); ?>>Needs Clearing</option>
                        <option value="partially_landscaped" <?php echo e(old('current_condition') == 'partially_landscaped' ? 'selected' : ''); ?>>Partially Landscaped</option>
                        <option value="other" <?php echo e(old('current_condition') == 'other' ? 'selected' : ''); ?>>Other</option>
                    </select>
                    <?php $__errorArgs = ['current_condition'];
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
            </div>
        </div>

        <!-- Project Description Section -->
        <div class="form-section">
            <h5><i class="fas fa-clipboard-list me-2"></i>Project Description</h5>
            <div class="mb-3">
                <label for="project_description" class="form-label">Describe Your Project <span class="text-danger">*</span></label>
                <textarea class="form-control <?php $__errorArgs = ['project_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="project_description" name="project_description" rows="4" 
                          placeholder="Please describe what you would like to achieve with your landscaping project..." 
                          required><?php echo e(old('project_description')); ?></textarea>
                <?php $__errorArgs = ['project_description'];
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
            <div class="mb-3">
                <label for="special_requirements" class="form-label">Special Requirements or Concerns</label>
                <textarea class="form-control <?php $__errorArgs = ['special_requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                          id="special_requirements" name="special_requirements" rows="3" 
                          placeholder="Any specific requirements, concerns, or questions you have..."><?php echo e(old('special_requirements')); ?></textarea>
                <?php $__errorArgs = ['special_requirements'];
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
        </div>

        <!-- Photos Section -->
        <div class="form-section">
            <h5><i class="fas fa-camera me-2"></i>Property Photos (Optional)</h5>
            <p class="text-muted small">Upload photos of your property to help us better understand your needs. Maximum 5 photos, 5MB each.</p>
            <div class="mb-3">
                <input type="file" class="form-control <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       id="photos" name="photos[]" accept="image/*" multiple>
                <?php $__errorArgs = ['photos.*'];
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
            <div id="photoPreview" class="mt-3"></div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex justify-content-end gap-2">
            <a href="<?php echo e(route('client-data.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane me-2"></i>Submit Request
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photos');
    const photoPreview = document.getElementById('photoPreview');
    let selectedFiles = [];

    photoInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Limit to 5 photos
        if (selectedFiles.length + files.length > 5) {
            alert('You can only upload a maximum of 5 photos.');
            return;
        }

        files.forEach(file => {
            // Check file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum file size is 5MB.`);
                return;
            }

            selectedFiles.push(file);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-photo" data-index="${selectedFiles.length - 1}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                photoPreview.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });

        // Clear the input
        photoInput.value = '';
    });

    // Remove photo
    photoPreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-photo')) {
            const button = e.target.closest('.remove-photo');
            const index = parseInt(button.dataset.index);
            
            // Remove from array
            selectedFiles.splice(index, 1);
            
            // Remove preview
            button.closest('.photo-preview').remove();
            
            // Update indices
            document.querySelectorAll('.remove-photo').forEach((btn, i) => {
                btn.dataset.index = i;
            });
        }
    });

    // On form submit, create a new FileList with selected files
    document.getElementById('requestForm').addEventListener('submit', function(e) {
        if (selectedFiles.length > 0) {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            photoInput.files = dataTransfer.files;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/site-visit-requests/create.blade.php ENDPATH**/ ?>