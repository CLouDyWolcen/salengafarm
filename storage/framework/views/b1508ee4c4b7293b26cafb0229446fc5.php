<?php
    use Illuminate\Support\Str;
?>



<?php $__env->startPush('styles'); ?>
<style>
/* Hide sidebar for plant care index page */
body.with-sidebar {
    display: block !important;
}
body.with-sidebar .dashboard-flex {
    display: block !important;
}
body.with-sidebar .dashboard-flex .sidebar {
    display: none !important;
}
body.with-sidebar .dashboard-flex .main-content {
    margin-left: 0 !important;
    width: 100% !important;
    padding-left: 0 !important;
}

.hover-card {
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.hover-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.hover-card .card-title {
    min-height: 30px;
}

.scientific-name-container {
    min-height: 24px;
    margin-bottom: 0.5rem;
}

.care-preview-badges {
    min-height: 70px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
}

.hover-card .card-footer {
    margin-top: auto;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 mb-2">🌿 Plant Guide Library</h1>
            <p class="text-muted">Learn how to care for your plants with our comprehensive guides</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" id="careSearchInput" placeholder="Search plants..." autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="careCategoryFilter">
                <option value="all">All Categories</option>
                <option value="shrub">Shrub</option>
                <option value="herbs">Herbs</option>
                <option value="palm">Palm</option>
                <option value="tree">Tree</option>
                <option value="grass">Grass</option>
                <option value="bamboo">Bamboo</option>
                <option value="fertilizer">Fertilizer</option>
            </select>
        </div>
    </div>

    <!-- Plants Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" id="carePlantsGrid">
        <?php $__empty_1 = true; $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col care-plant-item" data-category="<?php echo e($plant->category); ?>" data-name="<?php echo e(strtolower($plant->name)); ?>">
            <div class="card shadow-sm hover-card">
                <div class="card-img-container" style="height: 200px; overflow: hidden; background-color: #f8f9fa;">
                    <?php if($plant->photo_path): ?>
                        <img src="<?php echo e(asset('storage/' . $plant->photo_path)); ?>" class="card-img-top" alt="<?php echo e($plant->name); ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                            <i class="fas fa-leaf fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($plant->name); ?></h5>
                    <div class="scientific-name-container">
                        <?php if($plant->scientific_name): ?>
                            <p class="card-text text-muted small mb-0"><em><?php echo e($plant->scientific_name); ?></em></p>
                        <?php endif; ?>
                    </div>
                    <span class="badge bg-success mb-3"><?php echo e(ucfirst($plant->category)); ?></span>
                    
                    <?php if($plant->care_watering || $plant->care_sunlight || $plant->care_soil): ?>
                        <div class="care-preview-badges mt-2">
                            <?php if($plant->care_watering): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary me-1 mb-1">
                                    <i class="fas fa-tint"></i> Watering
                                </span>
                            <?php endif; ?>
                            <?php if($plant->care_sunlight): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning me-1 mb-1">
                                    <i class="fas fa-sun"></i> Sunlight
                                </span>
                            <?php endif; ?>
                            <?php if($plant->care_soil): ?>
                                <span class="badge bg-success bg-opacity-10 text-success me-1 mb-1">
                                    <i class="fas fa-seedling"></i> Soil
                                </span>
                            <?php endif; ?>
                            <?php if($plant->care_temperature): ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger me-1 mb-1">
                                    <i class="fas fa-thermometer-half"></i> Temp
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="care-preview-badges mt-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Care info coming soon
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <a href="<?php echo e(route('plant-care.show', $plant->id)); ?>" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-book-open"></i> View Care Guide
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No plants available yet. Check back soon!
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
    height: auto !important;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
}
.card-img-container {
    padding: 0 !important;
    margin: 0 !important;
}
.card-body {
    padding: 1rem !important;
}
.card-footer {
    padding: 0 1rem 1rem 1rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('careSearchInput');
    const categoryFilter = document.getElementById('careCategoryFilter');
    const plantItems = document.querySelectorAll('.care-plant-item');
    
    function filterPlants() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        
        plantItems.forEach(item => {
            const plantName = item.getAttribute('data-name');
            const plantCategory = item.getAttribute('data-category');
            
            const matchesSearch = plantName.includes(searchTerm);
            const matchesCategory = selectedCategory === 'all' || plantCategory === selectedCategory;
            
            item.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterPlants);
    categoryFilter.addEventListener('change', filterPlants);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/plant-care/index.blade.php ENDPATH**/ ?>