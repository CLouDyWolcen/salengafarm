<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Storage;
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;500;600&family=Pacifico&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/inventory.css')); ?>?v=2" rel="stylesheet">
    <link href="<?php echo e(asset('css/dashboard.css')); ?>?v=4" rel="stylesheet">
    <link href="<?php echo e(asset('css/loading.css')); ?>?v=5&t=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=2&t=<?php echo e(time()); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/button-spinner.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Update Stock Modal - Compact Table Styling */
        #updateStockModal .table th,
        #updateStockModal .table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
        #updateStockModal .table th:nth-child(1),
        #updateStockModal .table td:nth-child(1) {
            width: 40%;
        }
        #updateStockModal .table th:nth-child(2),
        #updateStockModal .table td:nth-child(2) {
            width: 30%;
        }
        #updateStockModal .table th:nth-child(3),
        #updateStockModal .table td:nth-child(3) {
            width: 30%;
        }
        #updateStockModal .stock-input {
            max-width: 100px;
            font-size: 0.9rem;
            padding: 0.375rem 0.5rem;
        }
        
        /* Instruction Overlay Styles */
        #instructionOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
            display: none;
        }
        
        #instructionOverlay.active {
            display: block;
        }
        
        .instruction-tooltip {
            position: absolute;
            background: white;
            border: 2px solid #4caf50;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            max-width: 300px;
            display: none;
        }
        
        .instruction-tooltip.active {
            display: block;
            animation: fadeInScale 0.3s ease-out;
        }
        
        .instruction-tooltip h6 {
            color: #4caf50;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1rem;
        }
        
        .instruction-tooltip p {
            margin: 0;
            font-size: 0.9rem;
            color: #333;
            line-height: 1.4;
        }
        
        .instruction-tooltip .close-instruction {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #999;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        
        .instruction-tooltip .close-instruction:hover {
            color: #333;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .instruction-highlight {
            position: relative;
            z-index: 9999;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.5);
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-light dashboard-page">

    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div style="padding-top: 0;">
        <!-- Main Content Area -->
                <div class="p-0">
                    <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px;">
                        <h2 class="mb-0 fs-5" style="font-size: 1.1rem;">Dashboard Overview</h2>
                    </div>
            <!-- Summary Cards Row - Reduced height via CSS -->
            <div class="row mb-4">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="card border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon me-3">
                                    <i class="fas fa-seedling fa-2x text-success opacity-50"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Total Plants in Stock</h6>
                                            <h2 class="card-title mb-0"><?php echo e($totalStock); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon me-3">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning opacity-50"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle mb-2 text-muted">Low Stock Items</h6>
                                            <h2 class="card-title mb-0"><?php echo e($lowStockItems->count()); ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main Content Row - Better organized with consistent heights -->
            <div class="main-content-row">
                <!-- Left Column - Analytics Summary and Low Stock Alerts -->
                <div class="col-md-3">
                    <!-- Analytics Summary -->
                    <div class="card analytics-summary-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fs-6">
                                    <i class="fas fa-chart-line me-2 text-success"></i>Analytics Summary
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h6 mb-1 text-success">
                                            <?php
                                                $totalRevenue = count($salesByCategory) > 0 ? array_sum(array_column($salesByCategory, 'revenue')) : 0;
                                            ?>
                                            ₱<?php echo e(number_format($totalRevenue, 0)); ?>

                                        </div>
                                        <small class="text-muted">Total Revenue</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h6 mb-1 text-info">
                                            <?php
                                                $totalQuantity = count($salesByCategory) > 0 ? array_sum(array_column($salesByCategory, 'quantity')) : 0;
                                            ?>
                                            <?php echo e($totalQuantity); ?>

                                        </div>
                                        <small class="text-muted">Plants Sold</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h6 mb-1 text-warning">
                                            <?php
                                                if (count($salesByCategory) > 0) {
                                                    $topCategory = collect($salesByCategory)->sortByDesc('revenue')->keys()->first();
                                                    echo $topCategory ? ucfirst($topCategory) : 'N/A';
                                                } else {
                                                    echo 'N/A';
                                                }
                                            ?>
                                        </div>
                                        <small class="text-muted">Top Category</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h6 mb-1 text-primary">
                                            <?php
                                                $avgTurnover = count($inventoryTurnover) > 0 ? array_sum($inventoryTurnover) / count($inventoryTurnover) : 0;
                                                echo number_format($avgTurnover, 1) . 'x';
                                            ?>
                                        </div>
                                        <small class="text-muted">Avg Turnover</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Low Stock Alerts -->
                    <div class="card low-stock-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fs-6">
                                    <i class="fas fa-exclamation-circle me-2 text-warning"></i>Low Stock Alerts
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php $__empty_1 = true; $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div>
                                                <h6 class="mb-0"><?php echo e(strtoupper($item->name)); ?></h6>
                                                <small class="text-muted"><?php echo e($item->category); ?></small>
                                            </div>
                                            <span class="badge bg-warning"><?php echo e($item->quantity); ?> left</span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="list-group-item">
                                        <p class="text-muted mb-0 text-center">No low stock items</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Middle Column - Stock Distribution -->
                <div class="col-md-6">
                    <!-- Chart Container with Tabs -->
                    <div class="card chart-container-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <ul class="nav nav-tabs card-header-tabs" id="chart-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock-chart" type="button" role="tab" aria-controls="stock-chart" aria-selected="true">
                                            <i class="fas fa-chart-pie me-1 text-primary"></i> Stock Distribution
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-chart" type="button" role="tab" aria-controls="sales-chart" aria-selected="false">
                                            <i class="fas fa-chart-bar me-1 text-success"></i> Sales by Category
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="trends-tab" data-bs-toggle="tab" data-bs-target="#trends-chart" type="button" role="tab" aria-controls="trends-chart" aria-selected="false">
                                            <i class="fas fa-chart-line me-1 text-info"></i> Sales Trends
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body tab-content">
                            <div class="tab-pane fade show active" id="stock-chart" role="tabpanel" aria-labelledby="stock-tab">
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                            <canvas id="stockDistributionChart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sales-chart" role="tabpanel" aria-labelledby="sales-tab">
                                <!-- Analytics Controls -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="salesPeriod">
                                            <option value="7">Last 7 days</option>
                                            <option value="30" selected>Last 30 days</option>
                                            <option value="90">Last 90 days</option>
                                            <option value="365">Last year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="salesType">
                                            <option value="quantity">By Quantity</option>
                                            <option value="revenue">By Revenue</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                                        <canvas id="salesDistributionChart"></canvas>
                                        </div>
                                </div>
                            <div class="tab-pane fade" id="trends-chart" role="tabpanel" aria-labelledby="trends-tab">
                                <!-- Trends Controls -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="trendsPeriod">
                                            <option value="7">Last 7 days</option>
                                            <option value="30" selected>Last 30 days</option>
                                            <option value="90">Last 90 days</option>
                                            <option value="365">Last year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select form-select-sm" id="trendsMetric">
                                            <option value="quantity">Daily Quantity</option>
                                            <option value="revenue">Daily Revenue</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <canvas id="salesTrendsChart"></canvas>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                <!-- Right Column - Quick Actions and Recent Plants -->
                <div class="col-md-3">
                    <!-- Quick Actions -->
                    <div class="card right-column-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fs-6">
                                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                                </h5>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-center">
                            <div class="d-grid gap-2 w-100">
                                <button class="btn btn-info text-white" id="updateStockBtn" data-bs-toggle="modal" data-bs-target="#updateStockModal">
                                    <i class="fas fa-sync me-2"></i>Update Stock
                                </button>
                                <?php if(Auth::user()->role === 'super_admin'): ?>
                                <button class="btn btn-info text-white" id="viewSystemLogsBtn">
                                    <i class="fas fa-file-alt me-2"></i>System Logs
                                </button>
                                <button class="btn btn-success text-white" id="viewSalesRecordsBtn">
                                    <i class="fas fa-receipt me-2"></i>Sales Records
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Plants -->
                    <div class="card recent-plants-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fs-6">
                                    <i class="fas fa-leaf me-2 text-success"></i>Recent Plants
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div>
                                                    <h6 class="mb-0">TINDALO</h6>
                                                    <small class="text-muted">Added 1 month ago</small>
                                            </div>
                                                <span class="badge bg-success">45</span>
                                        </div>
                                    </div>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <h6 class="mb-0">ROYAL PALM</h6>
                                                    <small class="text-muted">Added 1 month ago</small>
                                    </div>
                                                <span class="badge bg-success">34</span>
                                </div>
                            </div>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <h6 class="mb-0">DATE PALM</h6>
                                                    <small class="text-muted">Added 1 month ago</small>
                        </div>
                                                <span class="badge bg-success">11</span>
                    </div>
                </div>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <h6 class="mb-0">RADDISH</h6>
                                                    <small class="text-muted">Added 1 month ago</small>
                                                </div>
                                                <span class="badge bg-success">5</span>
                                            </div>
                                        </div>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <h6 class="mb-0">SPINACH</h6>
                                                    <small class="text-muted">Added 1 month ago</small>
                                                </div>
                                                <span class="badge bg-success">10</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Stock Modal -->
    <div class="modal fade" id="updateStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sync me-2"></i>Update Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-fullheight p-0">
                    <div class="row g-0 h-100">
                        <!-- Category Sidebar -->
                        <div class="col-md-2 border-end h-100 sidebar-scroll" style="position: sticky; top: 0; height: 100vh; overflow-y: auto;">
                            <div class="p-2">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action active" data-category="all">
                                        <i class="fas fa-layer-group me-2"></i>All Plants
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="shrub">
                                        <i class="fas fa-seedling me-2"></i>Shrub
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="herbs">
                                        <i class="fas fa-mortar-pestle me-2"></i>Herbs
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="palm">
                                        <i class="fas fa-tree me-2"></i>Palm
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="tree">
                                        <i class="fas fa-tree me-2"></i>Tree
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="grass">
                                        <i class="fas fa-leaf me-2"></i>Grass
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="bamboo">
                                        <i class="fas fa-tree me-2"></i>Bamboo
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" data-category="fertilizer">
                                        <i class="fas fa-prescription-bottle me-2"></i>Fertilizer
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Main Content -->
                        <div class="col-md-10 d-flex flex-column h-100">
                            <!-- Fixed Search Bar -->
                            <div class="p-3 border-bottom">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="stockSearchInput" 
                                           placeholder="Search plants...">
                                </div>
                            </div>
                            <!-- Scrollable Table -->
                            <div class="flex-grow-1 table-scroll" style="overflow-y: auto; max-height: 55vh;">
                                <div class="table-responsive h-100">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-header-sticky">
                                            <tr>
                                                <th>Plant Name</th>
                                                <th>Category</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody id="stockUpdateTableBody">
                                            <?php $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr data-category="<?php echo e($plant->category); ?>">
                                                <td><?php echo e($plant->name); ?></td>
                                                <td><?php echo e(ucfirst($plant->category)); ?></td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control stock-input" 
                                                           data-plant-id="<?php echo e($plant->id); ?>"
                                                           value="<?php echo e($plant->quantity); ?>"
                                                           min="0">
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Fixed Footer inside modal body -->
                            <div class="border-top p-3 bg-light">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-info text-white" id="saveStockUpdates">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Request Modal -->
    <div class="modal fade" id="clientRequestModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plant Information Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-scroll">
                    <form id="clientRequestForm">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Client Name</label>
                                <input type="text" class="form-control" id="client_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="client_email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Company Name (Optional)</label>
                                <input type="text" class="form-control" id="company_name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <select class="form-select" id="categoryFilter">
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
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="plantSearchInput" placeholder="Search plants...">
                            </div>
                        </div>

                        <div class="table-responsive client-table">
                            <table class="table table-hover">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th class="col-checkbox">
                                            <label class="container-checkbox">
                                                <input type="checkbox" id="selectAllPlants">
                                                <div class="checkmark"></div>
                                            </label>
                                        </th>
                                        <th class="col-name">Plant Name</th>
                                        <th class="col-code">Code</th>
                                        <th class="col-category">Category</th>
                                        <th class="col-scientific">Scientific Name</th>
                                        <th class="col-cost">Unit Cost</th>
                                        <th class="col-availability">Availability</th>
                                    </tr>
                                </thead>
                                <tbody id="plantsTableBody">
                                    <?php $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-category="<?php echo e(strtolower($plant->category)); ?>">
                                        <td class="col-checkbox">
                                            <label class="container-checkbox">
                                                <input type="checkbox" class="plant-checkbox" value="<?php echo e($plant->id); ?>" data-name="<?php echo e($plant->name); ?>">
                                                <div class="checkmark"></div>
                                            </label>
                                        </td>
                                        <td class="col-name"><?php echo e($plant->name); ?></td>
                                        <td class="col-code"><?php echo e($plant->code); ?></td>
                                        <td class="col-category"><?php echo e(ucfirst($plant->category)); ?></td>
                                        <td class="col-scientific"><?php echo e($plant->scientific_name); ?></td>
                                        <td class="col-cost">₱<?php echo e(number_format($plant->price, 2)); ?></td>
                                        <td class="col-availability"><?php echo e($plant->quantity > 0 ? 'In Stock' : 'Out of Stock'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <div class="alert alert-info" id="selectedPlantsInfo">
                                No plants selected
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="sendRequestBtn">Send Request</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Modal -->
    <div class="modal fade" id="lowStockModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Plants</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-fullheight p-0" style="min-height: 400px;">
                    <div class="row g-0 h-100">
                        <!-- Category Sidebar -->
                        <div class="col-md-3 border-end h-100 sidebar-scroll" style="position: sticky; top: 0; height: 100vh; overflow-y: auto;">
                            <div class="p-3">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action category-link" data-category="all">
                                        <i class="fas fa-layer-group me-2"></i>All Plants
                                    </a>
                                    <?php $__currentLoopData = $lowStockItems->groupBy('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="#" class="list-group-item list-group-item-action category-link" data-category="<?php echo e($category); ?>">
                                            <i class="fas fa-folder me-2"></i><?php echo e(ucfirst($category)); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <!-- Low Stock Information Table -->
                        <div class="col-md-9 flex-grow-1 table-scroll" style="overflow-y: auto; max-height: 70vh;">
                            <div class="table-responsive h-100">
                                <table class="table table-hover mb-0" id="lowStockTable">
                                    <thead class="table-header-sticky">
                                        <tr>
                                            <th>Plant Name</th>
                                            <th>Category</th>
                                            <th>Current Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr data-category="<?php echo e($item->category); ?>">
                                                <td><?php echo e($item->name); ?></td>
                                                <td><?php echo e(ucfirst($item->category)); ?></td>
                                                <td>
                                                    <span class="badge bg-warning"><?php echo e($item->quantity); ?> left</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/loading.js')); ?>"></script>
    <script src="<?php echo e(asset('js/push-notifications.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/push-notifications-global.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/dashboard.js')); ?>?v=2"></script>
    <script>
        $(document).ready(function() {
            
            // Ensure modals exist before adding event listeners
            const updateStockModal = document.getElementById('updateStockModal');

            if (updateStockModal) {
                $('#updateStockBtn').click(function() {
                    // Your existing code for handling the update stock button
                });
            } else {
                console.error('Element with ID "updateStockBtn" not found.');
            }

            // Initialize stock chart using the function from dashboard.js
            initStockChart('stockDistributionChart', {
                labels: <?php echo json_encode(array_keys($stockByCategory)); ?>,
                values: <?php echo json_encode(array_column($stockByCategory, 'quantity')); ?>

            });
            
            // Initialize sales distribution chart if data exists
            <?php if(count($salesByCategory) > 0): ?>
            initSalesChart('salesDistributionChart', {
                labels: <?php echo json_encode(array_keys($salesByCategory)); ?>,
                values: <?php echo json_encode(array_column($salesByCategory, 'quantity_percentage')); ?>,
                type: 'quantity'
            });
            <?php endif; ?>

            // Stock Update Modal Functionality - Preserved existing functionality
            $('#saveStockUpdates').click(function() {
                const updates = [];
                $('.stock-input').each(function() {
                    const input = $(this);
                    updates.push({
                        plant_id: input.data('plant-id'),
                        quantity: parseInt(input.val()) || 0
                    });
                });

                // Show Google Chrome-style spinner on button
                const $saveBtn = $(this);
                const originalBtnHtml = $saveBtn.html();
                $saveBtn.html('<span class="btn-spinner"></span>Saving...');
                $saveBtn.prop('disabled', true);

                // Send AJAX request to update stock
                $.ajax({
                    url: '/update-stock',
                    method: 'POST',
                    data: { 
                        updates: updates,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide modal
                        $('#updateStockModal').modal('hide');
                        
                        // Reset button state
                        $saveBtn.html(originalBtnHtml);
                        $saveBtn.prop('disabled', false);
                        
                        // Show success notification
                        if (window.PushNotifications) {
                            window.PushNotifications.show('success', 'Stock updated successfully!', true);
                        }
                        
                        // Reload page after showing notification
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while updating stock.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        // Reset button state
                        $saveBtn.html(originalBtnHtml);
                        $saveBtn.prop('disabled', false);
                        
                        // Show error notification
                        if (window.PushNotifications) {
                            window.PushNotifications.show('danger', errorMessage, false);
                        }
                    }
                });
            });

            // Category filter in Low Stock Modal - Preserved existing functionality
            $('.category-link').click(function(e) {
                e.preventDefault();
                
                // Update active state
                $('.category-link').removeClass('active');
                $(this).addClass('active');
                
                const selectedCategory = $(this).data('category');
                $('#lowStockTable tbody tr').each(function() {
                    const rowCategory = $(this).data('category');
                    if (rowCategory === selectedCategory || selectedCategory === 'all') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Add search functionality for stock table
            $('#stockSearchInput').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('#stockUpdateTableBody tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    if (rowText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSidebar();
            });
        });
    </script>
    
    <!-- Instruction Overlay -->
    <div id="instructionOverlay"></div>
    
    <!-- Instruction System Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const instructionBtn = document.getElementById('instructionBtn');
        const overlay = document.getElementById('instructionOverlay');
        
        // Only setup if elements exist
        if (!instructionBtn || !overlay) {
            return;
        }
        
        let instructionsActive = false;
        
        const instructions = [
            {
                selector: '.col-md-6:first-child .card',
                title: 'Total Plants in Stock',
                text: 'Shows the total number of plants currently available in your inventory.'
            },
            {
                selector: '.col-md-6:last-child .card',
                title: 'Low Stock Items',
                text: 'Displays the count of plants with stock levels below 10 units.'
            },
            {
                selector: '.low-stock-card',
                title: 'Low Stock Alerts',
                text: 'Lists all plants that are running low on stock. Keep an eye on these items to reorder before they run out.'
            },
            {
                selector: '.chart-container-card',
                title: 'Stock & Sales Distribution',
                text: 'View your inventory distribution by category (Stock Distribution) or see sales performance by category (Sales by Category). Switch between tabs to see different views.'
            },
            {
                selector: '.right-column-card',
                title: 'Quick Actions',
                text: 'Click "Update Stock" to quickly modify inventory quantities for multiple plants at once.'
            },
            {
                selector: '.recent-plants-card',
                title: 'Recent Plants',
                text: 'Shows the most recently added plants to your inventory with their current stock levels.'
            }
        ];
        
        instructionBtn.addEventListener('click', function() {
            if (instructionsActive) {
                hideInstructions();
            } else {
                showInstructions();
            }
        });
        
        overlay.addEventListener('click', function() {
            hideInstructions();
        });
        
        function showInstructions() {
            instructionsActive = true;
            overlay.classList.add('active');
            instructionBtn.innerHTML = '<i class="fas fa-times"></i> Close';
            instructionBtn.classList.remove('btn-outline-info');
            instructionBtn.classList.add('btn-danger');
            
            instructions.forEach((instruction, index) => {
                const element = document.querySelector(instruction.selector);
                if (element) {
                    // Add highlight
                    element.classList.add('instruction-highlight');
                    
                    // Create tooltip
                    const tooltip = document.createElement('div');
                    tooltip.className = 'instruction-tooltip';
                    tooltip.innerHTML = `
                        <button class="close-instruction" onclick="hideInstructions()">&times;</button>
                        <h6>${instruction.title}</h6>
                        <p>${instruction.text}</p>
                    `;
                    
                    // Position tooltip
                    document.body.appendChild(tooltip);
                    const rect = element.getBoundingClientRect();
                    tooltip.style.top = (rect.top + window.scrollY - 10) + 'px';
                    tooltip.style.left = (rect.right + 15) + 'px';
                    
                    // Adjust if tooltip goes off screen
                    setTimeout(() => {
                        const tooltipRect = tooltip.getBoundingClientRect();
                        if (tooltipRect.right > window.innerWidth) {
                            tooltip.style.left = (rect.left - tooltipRect.width - 15) + 'px';
                        }
                        if (tooltipRect.bottom > window.innerHeight) {
                            tooltip.style.top = (rect.bottom + window.scrollY - tooltipRect.height) + 'px';
                        }
                    }, 10);
                    
                    // Show with delay for staggered effect
                    setTimeout(() => {
                        tooltip.classList.add('active');
                    }, index * 100);
                }
            });
        }
        
        function hideInstructions() {
            instructionsActive = false;
            overlay.classList.remove('active');
            instructionBtn.innerHTML = '<i class="fas fa-question-circle"></i> Help';
            instructionBtn.classList.remove('btn-danger');
            instructionBtn.classList.add('btn-outline-info');
            
            // Remove all tooltips and highlights
            document.querySelectorAll('.instruction-tooltip').forEach(el => el.remove());
            document.querySelectorAll('.instruction-highlight').forEach(el => {
                el.classList.remove('instruction-highlight');
            });
        }
        
        // Make hideInstructions globally accessible
        window.hideInstructions = hideInstructions;
    });
    </script>

    <!-- System Logs Modal (Super Admin Only) -->
    <?php if(Auth::user()->role === 'super_admin'): ?>
    <div class="modal fade" id="systemLogsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>System Logs</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Filters -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Log Level</label>
                            <select id="logLevel" class="form-select form-select-sm">
                                <option value="all">All Levels</option>
                                <option value="info">INFO</option>
                                <option value="error">ERROR</option>
                                <option value="warning">WARNING</option>
                                <option value="debug">DEBUG</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-1">Lines</label>
                            <select id="logLines" class="form-select form-select-sm">
                                <option value="50">50</option>
                                <option value="100" selected>100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Search</label>
                            <input type="text" id="logSearch" class="form-control form-control-sm" placeholder="Search in logs...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small mb-1">&nbsp;</label>
                            <button class="btn btn-primary btn-sm w-100" onclick="loadLogs()">
                                <i class="fas fa-filter"></i> Apply
                            </button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">File Size</span>
                                        <strong id="logSize" class="text-primary">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Entries</span>
                                        <strong id="logCount" class="text-success">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Last Updated</span>
                                        <strong id="logTime" class="text-info">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mb-3 d-flex gap-2">
                        <button class="btn btn-success btn-sm" onclick="downloadLogs()">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="clearLogs()">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button class="btn btn-info btn-sm" onclick="loadLogs()">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>

                    <!-- Logs Table -->
                    <div class="table-responsive" style="max-height: 450px; border: 1px solid #dee2e6; border-radius: 4px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem; width: 100%;">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="width: 140px; padding: 10px 12px;">Timestamp</th>
                                    <th style="width: 90px; padding: 10px 12px;">Level</th>
                                    <th style="width: 40%; padding: 10px 12px;">Message</th>
                                    <th style="width: 40%; padding: 10px 12px;">Context</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="mt-2 text-muted">Loading logs...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // System Logs Functions
        function loadLogs() {
            const level = document.getElementById('logLevel').value;
            const lines = document.getElementById('logLines').value;
            const search = document.getElementById('logSearch').value;

            // Show loading
            document.getElementById('logsTableBody').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">Loading logs...</div>
                    </td>
                </tr>
            `;

            fetch(`/admin/logs/fetch?level=${level}&lines=${lines}&search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(data => {
                    // Update stats
                    document.getElementById('logSize').textContent = (data.logSize / 1024).toFixed(2) + ' KB';
                    document.getElementById('logCount').textContent = data.count;
                    document.getElementById('logTime').textContent = new Date().toLocaleTimeString();

                    // Update table
                    const tbody = document.getElementById('logsTableBody');
                    if (data.logs.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <div class="text-muted">No logs found</div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    tbody.innerHTML = data.logs.map(log => {
                        const levelConfig = {
                            'INFO': { class: 'bg-info', icon: 'info-circle' },
                            'ERROR': { class: 'bg-danger', icon: 'exclamation-circle' },
                            'WARNING': { class: 'bg-warning text-dark', icon: 'exclamation-triangle' },
                            'DEBUG': { class: 'bg-secondary', icon: 'bug' }
                        };
                        const config = levelConfig[log.level] || { class: 'bg-secondary', icon: 'question' };

                        // Format JSON context for better readability
                        let formattedContext = log.context;
                        if (formattedContext && (formattedContext.startsWith('{') || formattedContext.startsWith('['))) {
                            try {
                                const parsed = JSON.parse(formattedContext);
                                formattedContext = JSON.stringify(parsed, null, 2);
                            } catch (e) {
                                // Keep original if not valid JSON
                            }
                        }

                        return `
                            <tr style="border-bottom: 2px solid #f0f0f0;">
                                <td style="padding: 12px; vertical-align: top;">
                                    <span class="text-muted" style="font-family: 'Courier New', monospace; font-size: 0.75rem; display: block; line-height: 1.3; white-space: nowrap;">
                                        ${log.timestamp}
                                    </span>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <span class="badge ${config.class}" style="font-size: 0.7rem; padding: 4px 8px; white-space: nowrap;">
                                        <i class="fas fa-${config.icon} me-1"></i>${log.level}
                                    </span>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <div style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; line-height: 1.5;">
                                        ${log.message}
                                    </div>
                                </td>
                                <td style="padding: 12px; vertical-align: top;">
                                    <pre class="text-muted mb-0" style="font-family: 'Courier New', monospace; font-size: 0.7rem; background: #f8f9fa; padding: 8px; border-radius: 4px; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; line-height: 1.4; max-height: 250px; overflow-y: auto;">${formattedContext}</pre>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading logs:', error);
                    document.getElementById('logsTableBody').innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4 text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <div>Error loading logs</div>
                            </td>
                        </tr>
                    `;
                });
        }

        function downloadLogs() {
            window.location.href = '/admin/logs/download';
        }

        function clearLogs() {
            if (confirm('Are you sure you want to clear all logs?\n\nA backup will be created automatically.')) {
                fetch('/admin/logs/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('✓ Logs cleared successfully!\nBackup has been saved.');
                    loadLogs();
                })
                .catch(error => {
                    alert('✗ Error clearing logs');
                    console.error(error);
                });
            }
        }

        // Open modal and load logs
        document.getElementById('viewSystemLogsBtn')?.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('systemLogsModal'));
            modal.show();
            loadLogs();
        });
    </script>

    <!-- Sales Records Modal (Super Admin Only) -->
    <div class="modal fade" id="salesRecordsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-receipt me-2"></i>Sales Records</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Summary Cards -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-shopping-cart fa-lg text-success opacity-50"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Total Sales</small>
                                            <h5 class="mb-0" id="totalSalesCount">-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-box fa-lg text-primary opacity-50"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Total Quantity</small>
                                            <h5 class="mb-0" id="totalQuantitySold">-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-peso-sign fa-lg text-warning opacity-50"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Total Revenue</small>
                                            <h5 class="mb-0" id="totalRevenue">-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Filter Buttons -->
                    <div class="mb-3">
                        <div class="btn-group btn-group-sm mb-2" role="group">
                            <button type="button" class="btn btn-outline-success" data-filter="today" onclick="applySalesQuickFilter('today')">
                                <i class="fas fa-calendar-day me-1"></i>Today
                            </button>
                            <button type="button" class="btn btn-outline-success" data-filter="week" onclick="applySalesQuickFilter('week')">
                                <i class="fas fa-calendar-week me-1"></i>This Week
                            </button>
                            <button type="button" class="btn btn-outline-success" data-filter="month" onclick="applySalesQuickFilter('month')">
                                <i class="fas fa-calendar-alt me-1"></i>This Month
                            </button>
                            <button type="button" class="btn btn-outline-success" data-filter="year" onclick="applySalesQuickFilter('year')">
                                <i class="fas fa-calendar me-1"></i>This Year
                            </button>
                            <button type="button" class="btn btn-outline-secondary active" data-filter="all" onclick="applySalesQuickFilter('all')">
                                <i class="fas fa-infinity me-1"></i>All Time
                            </button>
                        </div>
                        
                        <!-- Custom Date Range -->
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small mb-1">Start Date</label>
                                <input type="date" id="salesStartDate" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small mb-1">End Date</label>
                                <input type="date" id="salesEndDate" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small mb-1">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-sm flex-fill" onclick="loadSalesRecords()">
                                        <i class="fas fa-filter"></i> Apply Custom
                                    </button>
                                    <button class="btn btn-secondary btn-sm flex-fill" onclick="resetSalesFilters()">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div class="table-responsive" style="max-height: 450px; border: 1px solid #dee2e6; border-radius: 4px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="padding: 10px 12px;">ID</th>
                                    <th style="padding: 10px 12px;">Date</th>
                                    <th style="padding: 10px 12px;">Plant Name</th>
                                    <th style="padding: 10px 12px;">Qty</th>
                                    <th style="padding: 10px 12px;">Unit Price</th>
                                    <th style="padding: 10px 12px;">Total</th>
                                    <th style="padding: 10px 12px;">Customer</th>
                                    <th style="padding: 10px 12px;">Payment</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody">
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div class="mt-2 text-muted">Loading sales records...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sales Records Functions
        let currentSalesFilter = 'all'; // Default to all time
        
        function applySalesQuickFilter(filter) {
            currentSalesFilter = filter;
            
            // Update button states
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
            
            // Calculate date range based on filter
            const today = new Date();
            let startDate = null;
            let endDate = null;
            
            switch(filter) {
                case 'today':
                    startDate = today.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay()); // Start of week (Sunday)
                    startDate = weekStart.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'month':
                    const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                    startDate = monthStart.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'year':
                    const yearStart = new Date(today.getFullYear(), 0, 1);
                    startDate = yearStart.toISOString().split('T')[0];
                    endDate = today.toISOString().split('T')[0];
                    break;
                case 'all':
                    // No date filter
                    break;
            }
            
            // Update date inputs
            document.getElementById('salesStartDate').value = startDate || '';
            document.getElementById('salesEndDate').value = endDate || '';
            
            // Load records
            loadSalesRecords(startDate, endDate);
        }
        
        function loadSalesRecords(startDate = null, endDate = null) {
            // If no dates provided, use the input values
            if (startDate === null) {
                startDate = document.getElementById('salesStartDate').value;
            }
            if (endDate === null) {
                endDate = document.getElementById('salesEndDate').value;
            }
            
            // Show loading state
            const tbody = document.getElementById('salesTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">Loading sales records...</div>
                    </td>
                </tr>
            `;
            
            let url = '/walk-in/records';
            const params = new URLSearchParams();
            
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displaySalesRecords(data.data.data);
                        updateSalesSummary(data.data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading sales:', error);
                    document.getElementById('salesTableBody').innerHTML = 
                        '<tr><td colspan="8" class="text-center text-danger py-4"><i class="fas fa-exclamation-triangle me-2"></i>Error loading sales records</td></tr>';
                });
        }
        
        function displaySalesRecords(sales) {
            const tbody = document.getElementById('salesTableBody');
            
            if (!sales || sales.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No sales records found</td></tr>';
                // Reset summary to zero
                document.getElementById('totalSalesCount').textContent = '0';
                document.getElementById('totalQuantitySold').textContent = '0';
                document.getElementById('totalRevenue').textContent = '₱0.00';
                return;
            }
            
            tbody.innerHTML = sales.map(sale => {
                const saleDate = sale.sale_date ? new Date(sale.sale_date).toLocaleDateString('en-US', { 
                    month: 'short', day: 'numeric', year: 'numeric' 
                }) : 'N/A';
                
                const plantName = sale.plant ? sale.plant.name : 'N/A';
                const customerName = sale.customer_name || 'Walk-in';
                const paymentBadge = sale.payment_method === 'cash' ? 
                    '<span class="badge bg-success">Cash</span>' : 
                    '<span class="badge bg-primary">' + sale.payment_method.charAt(0).toUpperCase() + sale.payment_method.slice(1) + '</span>';
                
                return `
                    <tr>
                        <td style="padding: 8px 12px;">#${sale.id}</td>
                        <td style="padding: 8px 12px;">${saleDate}</td>
                        <td style="padding: 8px 12px;"><strong>${plantName}</strong></td>
                        <td style="padding: 8px 12px;">${sale.quantity}</td>
                        <td style="padding: 8px 12px;">₱${parseFloat(sale.price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        <td style="padding: 8px 12px;"><strong>₱${parseFloat(sale.total_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong></td>
                        <td style="padding: 8px 12px;">${customerName}</td>
                        <td style="padding: 8px 12px;">${paymentBadge}</td>
                    </tr>
                `;
            }).join('');
        }
        
        function updateSalesSummary(sales) {
            const totalSales = sales.length;
            const totalQuantity = sales.reduce((sum, sale) => sum + parseInt(sale.quantity), 0);
            const totalRevenue = sales.reduce((sum, sale) => sum + parseFloat(sale.total_price), 0);
            
            document.getElementById('totalSalesCount').textContent = totalSales.toLocaleString();
            document.getElementById('totalQuantitySold').textContent = totalQuantity.toLocaleString();
            document.getElementById('totalRevenue').textContent = '₱' + totalRevenue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        
        function resetSalesFilters() {
            // Reset to all time
            applySalesQuickFilter('all');
        }
        
        // Open modal and load sales (default to all time)
        document.getElementById('viewSalesRecordsBtn')?.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('salesRecordsModal'));
            modal.show();
            applySalesQuickFilter('all'); // Load all records by default
        });
    </script>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\CODING\my_Inventory\resources\views/dashboard.blade.php ENDPATH**/ ?>