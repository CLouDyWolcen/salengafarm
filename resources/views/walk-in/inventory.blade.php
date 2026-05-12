@extends('layouts.public')

@section('title', 'Inventory - Salenga Farm')

@section('content')
<style>
    /* Mobile responsive - force cards side by side */
    @media (max-width: 767px) {
        /* Stat cards - 2x2 grid */
        .row.g-2.mb-3 > .col-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        
        /* Recent Sales and Low Stock Alert - side by side */
        .row.g-2 > .col-6 {
            flex: 0 0 50% !important;
            max-width: 50% !important;
        }
        
        /* Make Back to Sales and Refresh Data buttons icon-only on mobile */
        .btn-group .btn {
            width: 45px !important;
            height: 45px !important;
            padding: 0 !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .btn-group .btn i {
            margin: 0 !important;
            font-size: 1.2rem !important;
        }
        
        .btn-group .btn .btn-text {
            display: none !important;
        }
        
        /* CRITICAL: Override inventory.css that hides columns - show ALL columns */
        .table th:nth-child(2),
        .table td:nth-child(2),
        .table th:nth-child(4),
        .table td:nth-child(4),
        .table th:nth-child(5),
        .table td:nth-child(5) {
            display: table-cell !important;
        }
        
        /* Inventory table - make horizontally scrollable with all columns visible */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            display: block !important;
        }
        
        /* Force table to show all columns */
        .table {
            min-width: 700px !important;
            display: table !important;
            width: auto !important;
            table-layout: auto !important;
        }
        
        .table thead,
        .table tbody {
            display: table-row-group !important;
        }
        
        .table tr {
            display: table-row !important;
        }
        
        .table th,
        .table td {
            display: table-cell !important;
            white-space: nowrap !important;
            padding: 0.5rem 0.3rem !important;
            font-size: 0.75rem !important;
        }
        
        /* Compact inputs on mobile */
        .table input.form-control {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.3rem !important;
        }
        
        /* Compact buttons on mobile */
        .table .btn {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
            width: auto !important;
        }
    }
    
    /* Fix spacing between Low Stock Alert header and first item */
    #low-stock-list .list-group-item {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        margin-bottom: 0 !important;
        border: none !important;
    }
    
    #low-stock-list .list-group-item:first-child {
        padding-top: 0.75rem !important;
    }
    
    /* Status badge styles */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-badge.status-good {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-badge.status-medium {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-badge.status-low {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

    <div class="container-fluid" style="min-height: 100vh; display: flex; flex-direction: column; padding: 1rem; background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);">
        <!-- Sidebar Toggle Button for Mobile/Tablet -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar" style="position: fixed; top: 10px; right: 10px; z-index: 1000; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0 fs-4" style="color: #2e7d32; font-weight: 600; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-boxes me-2" style="color: #4caf50;"></i>Point of Sale Inventory Management
                    </h2>
                    <div class="btn-group">
                        <a href="{{ route('walk-in.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); border: none; box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);">
                            <i class="fas fa-arrow-left"></i><span class="btn-text ms-1">Back to Sales</span>
                        </a>
                        <button id="refresh-btn" class="btn btn-success ms-2" style="background: linear-gradient(135deg, #81c784 0%, #66bb6a 100%); border: none; box-shadow: 0 2px 4px rgba(102, 187, 106, 0.3);">
                            <i class="fas fa-sync-alt"></i><span class="btn-text ms-1">Refresh Data</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-2 mb-3">
            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0" style="height: 70px; border-radius: 10px; background: linear-gradient(135deg, #ffffff 0%, #f1f8f4 100%);">
                    <div class="card-body d-flex align-items-center justify-content-center" style="padding: 0.6rem 0.8rem !important; height: 100%;">
                        <div class="d-flex align-items-center w-100">
                            <div class="flex-shrink-0 me-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);">
                                    <i class="fas fa-leaf text-white" style="font-size: 1rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Total Inventory</p>
                                <h3 class="mb-0 fw-bold" style="font-size: 1.25rem; color: #2e7d32;" id="total-inventory-count">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0" style="height: 70px; border-radius: 10px; background: linear-gradient(135deg, #ffffff 0%, #fff8e1 100%);">
                    <div class="card-body d-flex align-items-center justify-content-center" style="padding: 0.6rem 0.8rem !important; height: 100%;">
                        <div class="d-flex align-items-center w-100">
                            <div class="flex-shrink-0 me-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ffb74d 0%, #ffa726 100%);">
                                    <i class="fas fa-exclamation-triangle text-white" style="font-size: 1rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Low Stock Items</p>
                                <h3 class="mb-0 fw-bold" style="font-size: 1.25rem; color: #f57c00;" id="low-stock-count">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0" style="height: 70px; border-radius: 10px; background: linear-gradient(135deg, #ffffff 0%, #e0f2f1 100%);">
                    <div class="card-body d-flex align-items-center justify-content-center" style="padding: 0.6rem 0.8rem !important; height: 100%;">
                        <div class="d-flex align-items-center w-100">
                            <div class="flex-shrink-0 me-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #4db6ac 0%, #26a69a 100%);">
                                    <i class="fas fa-shopping-cart text-white" style="font-size: 1rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Today's Sales</p>
                                <h3 class="mb-0 fw-bold" style="font-size: 1.25rem; color: #00897b;" id="today-sales-count">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0" style="height: 70px; border-radius: 10px; background: linear-gradient(135deg, #ffffff 0%, #e3f2fd 100%);">
                    <div class="card-body d-flex align-items-center justify-content-center" style="padding: 0.6rem 0.8rem !important; height: 100%;">
                        <div class="d-flex align-items-center w-100">
                            <div class="flex-shrink-0 me-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #81c784 0%, #66bb6a 100%);">
                                    <i class="fas fa-peso-sign text-white" style="font-size: 1rem;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Revenue (30 days)</p>
                                <h3 class="mb-0 fw-bold" style="font-size: 1.25rem; color: #2e7d32;" id="total-revenue">₱0.00</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row" style="flex: 1; overflow: hidden;">
            <div class="col-md-9" style="display: flex; flex-direction: column;">
                <div class="card mb-4" style="flex: 1; display: flex; flex-direction: column; overflow: hidden; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); color: white; border-radius: 12px 12px 0 0; border: none;">
                        <h5 class="mb-0" style="font-weight: 600; font-family: 'Poppins', sans-serif;"><i class="fas fa-seedling me-2"></i>Inventory Management</h5>
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text" style="background-color: rgba(255, 255, 255, 0.9); border: none;"><i class="fas fa-search text-success"></i></span>
                            <input type="text" class="form-control" id="inventory-search" placeholder="Search plants..." style="border: none;">
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th>Plant Name</th>
                                        <th>Code</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="inventory-table-body">
                                    @foreach($plants as $plant)
                                        <tr data-id="{{ $plant->id }}">
                                            <td class="align-middle">{{ $plant->name }}</td>
                                            <td class="align-middle">{{ $plant->code }}</td>
                                            <td class="align-middle">
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" class="form-control form-control-sm editable-price" value="{{ $plant->price }}" min="0" step="0.01">
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <input type="number" class="form-control form-control-sm editable-quantity" value="{{ $plant->quantity }}" min="0" style="width: 80px;">
                                            </td>
                                            <td class="align-middle">
                                                @if($plant->quantity < 5)
                                                    <span class="status-badge status-low">
                                                        <i class="fas fa-exclamation-circle"></i> Low
                                                    </span>
                                                @elseif($plant->quantity < 10)
                                                    <span class="status-badge status-medium">
                                                        <i class="fas fa-exclamation-triangle"></i> Medium
                                                    </span>
                                                @else
                                                    <span class="status-badge status-good">
                                                        <i class="fas fa-check-circle"></i> Good
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-sm btn-success save-btn" data-id="{{ $plant->id }}">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12" style="display: flex; flex-direction: column;">
                <div class="row g-2">
                    <!-- Recent Sales Card -->
                    <div class="col-md-12 col-6">
                        <div class="card sidebar-card" style="height: 280px; display: flex; flex-direction: column; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px;">
                            <div class="card-header" style="background: linear-gradient(135deg, #4db6ac 0%, #26a69a 100%); color: white; border-radius: 12px 12px 0 0; border: none; padding: 0.5rem 0.75rem;">
                                <h5 class="mb-0" style="font-weight: 600; font-family: 'Poppins', sans-serif; font-size: 0.9rem;"><i class="fas fa-receipt me-2"></i>Recent Sales</h5>
                            </div>
                            <div class="card-body" style="flex: 1; overflow-y: auto; padding: 0.75rem;">
                                <div class="list-group list-group-flush" id="recent-sales-list">
                                    @forelse($recentSales as $sale)
                                        <div class="list-group-item" style="border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid #e0e0e0; background-color: white;">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div style="flex: 1; min-width: 0;">
                                                    <h6 class="mb-1" style="font-size: 0.85rem; font-weight: 600; color: #2e7d32; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $sale->plant->name }}">{{ $sale->plant->name }}</h6>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        {{ $sale->created_at->format('M d, Y h:i A') }}
                                                    </small>
                                                </div>
                                                <span class="badge" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); color: white; font-size: 0.75rem; padding: 0.35rem 0.6rem;">{{ $sale->quantity }} sold</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-receipt fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem;">No recent sales</p>
                                            <small class="text-muted" style="font-size: 0.8rem;">Sales will appear here</small>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Card -->
                    <div class="col-md-12 col-6">
                        <div class="card sidebar-card" style="height: 280px; display: flex; flex-direction: column; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px;">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #ffb74d 0%, #ffa726 100%); color: white; border-radius: 12px 12px 0 0; border: none; padding: 0.5rem 0.75rem;">
                                <h5 class="mb-0" style="font-weight: 600; font-family: 'Poppins', sans-serif; font-size: 0.9rem;"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h5>
                                <button id="show-all-low-stock" class="btn btn-sm" style="background-color: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3); padding: 0.25rem 0.5rem; font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#lowStockModal">
                                    <i class="fas fa-list-ul me-1"></i> Show All
                                </button>
                            </div>
                            <div class="card-body" style="flex: 1; overflow-y: auto; padding: 1rem; padding-top: 0.5rem !important;">
                                <div class="list-group list-group-flush" id="low-stock-list">
                                    <!-- Low stock items will be populated here via AJAX -->
                                    <div class="list-group-item text-center py-4" style="background-color: transparent; border: none;">
                                        <div class="spinner-border" style="color: #ffa726;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading low stock items...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast-container"></div>
    </div>

    <!-- Low Stock Modal -->
    <div class="modal fade" id="lowStockModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Plants</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-fullheight p-0" style="min-height: 400px;">
                    <div class="row g-0 h-100">
                        <div class="col-md-3 border-end p-3">
                            <div class="form-group mb-3">
                                <label for="category-filter" class="form-label">Filter by Category</label>
                                <select class="form-select" id="category-filter">
                                    <option value="all">All Categories</option>
                                    <!-- Categories will be populated here via JavaScript -->
                                </select>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>These plants are running low on stock and might need reordering soon.</span>
                            </div>
                        </div>
                        <div class="col-md-9 flex-grow-1 table-scroll" style="overflow-y: auto; max-height: 70vh;">
                            <div class="table-responsive h-100">
                                <table class="table table-hover mb-0" id="lowStockTable">
                                    <thead class="table-header-sticky">
                                        <tr>
                                            <th>Plant Name</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lowStockModalBody">
                                        <!-- Low stock items will be populated here via JavaScript -->
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

    <!-- Sales Records Modal -->
    <div class="modal fade" id="salesRecordsModal" tabindex="-1" aria-labelledby="salesRecordsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesRecordsModalLabel">Sales Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text">From</span>
                                <input type="date" class="form-control" id="start-date">
                                <span class="input-group-text">To</span>
                                <input type="date" class="form-control" id="end-date">
                                <button class="btn btn-primary" id="filter-records-btn">Filter</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6;">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                                <tr>
                                    <th>Date</th>
                                    <th>Plant</th>
                                    <th>Height</th>
                                    <th>Spread</th>
                                    <th>Spacing</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Customer</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody id="sales-records-body">
                                <!-- Sales records will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="sales-records-pagination" class="d-flex justify-content-center mt-3" style="padding: 10px 0;">
                        <!-- Pagination will be added here dynamically -->
                    </div>
                    
                    <div id="no-records-message" class="text-center py-3 d-none">
                        <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
                        <p>No sales records found for the selected period.</p>
                    </div>
                    
                    <div id="records-loading" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading records...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Hide loading overlay when page is fully loaded
            setTimeout(function() {
                $('#loading-overlay').fadeOut();
            }, 500);

            // Load inventory statistics on page load
            loadInventoryStats();

            // Refresh button click handler
            $('#refresh-btn').on('click', function() {
                loadInventoryStats();
                showToast('Refresh', 'Data has been refreshed', 'success');
            });

            // Search functionality - improved to search specifically in plant name
            $('#inventory-search').on('keyup', function() {
                var value = $(this).val().toLowerCase().trim();
                
                if (value === '') {
                    // Show all rows if search is empty
                    $('#inventory-table-body tr').show();
                } else {
                    $('#inventory-table-body tr').each(function() {
                        var row = $(this);
                        // Get the plant name from the first column
                        var plantName = row.find('td:first').text().toLowerCase().trim();
                        // Get the plant code from the second column
                        var plantCode = row.find('td:eq(1)').text().toLowerCase().trim();
                        
                        // Show row if search value is found in plant name or code
                        if (plantName.indexOf(value) > -1 || plantCode.indexOf(value) > -1) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    });
                }
            });

            // Category filter for low stock modal
            $('#category-filter').on('change', function() {
                var category = $(this).val();
                if (category === 'all') {
                    $('#lowStockTable tbody tr').show();
                } else {
                    $('#lowStockTable tbody tr').hide();
                    $('#lowStockTable tbody tr[data-category="' + category + '"]').show();
                }
            });

            // Save button click handler
            $('.save-btn').on('click', function() {
                var btn = $(this);
                var originalBtnText = btn.html();
                var plantId = btn.data('id');
                var row = btn.closest('tr');
                var newQuantity = row.find('.editable-quantity').val();
                var newPrice = row.find('.editable-price').val();

                // Validate quantity input
                if (newQuantity === '' || isNaN(newQuantity) || parseInt(newQuantity) < 0) {
                    showToast('Error', 'Please enter a valid quantity', 'error');
                    return;
                }
                
                // Validate price input
                if (newPrice === '' || isNaN(newPrice) || parseFloat(newPrice) < 0) {
                    showToast('Error', 'Please enter a valid price', 'error');
                    return;
                }

                // Show loading state
                btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                btn.prop('disabled', true);

                // Send AJAX request to update inventory
                $.ajax({
                    url: '{{ route("walk-in.inventory.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        updates: [
                            {
                                id: plantId,
                                quantity: parseInt(newQuantity),
                                price: parseFloat(newPrice)
                            }
                        ]
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update stock display (column 3)
                            btn.closest('tr').find('td:eq(3) input').val(newQuantity);

                            // Update status badge (column 4)
                            var statusCell = btn.closest('tr').find('td:eq(4)');
                            var quantity = parseInt(newQuantity);

                            if (quantity < 5) {
                                statusCell.html('<span class="status-badge status-low"><i class="fas fa-exclamation-circle"></i> Low</span>');
                            } else if (quantity < 10) {
                                statusCell.html('<span class="status-badge status-medium"><i class="fas fa-exclamation-triangle"></i> Medium</span>');
                            } else {
                                statusCell.html('<span class="status-badge status-good"><i class="fas fa-check-circle"></i> Good</span>');
                            }

                            // Refresh inventory stats
                            loadInventoryStats();

                            // Show success toast
                            showToast('Success', 'Stock updated successfully', 'success');
                        } else {
                            showToast('Error', response.message, 'error');
                        }

                        // Reset button state
                        btn.html(originalBtnText);
                        btn.prop('disabled', false);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        showToast('Error', 'Failed to update stock', 'error');
                        btn.html(originalBtnText);
                        btn.prop('disabled', false);
                    }
                });
            });

            // Function to load inventory statistics
            function loadInventoryStats() {
                $.ajax({
                    url: '{{ route("walk-in.inventory.stats") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            // Update low stock list
                            updateLowStockList(data.low_stock);

                            // Update summary cards
                            updateSummaryCards();
                        } else {
                            showToast('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        showToast('Error', 'Failed to load inventory statistics', 'error');
                    }
                });
            }

            // Function to update low stock list
            function updateLowStockList(lowStockItems) {
                var lowStockList = $('#low-stock-list');
                var lowStockModalBody = $('#lowStockModalBody');
                var categoryFilter = $('#category-filter');
                var showAllButton = $('#show-all-low-stock');

                lowStockList.empty();
                lowStockModalBody.empty();

                // Reset category filter options
                categoryFilter.find('option:not(:first)').remove();
                var categories = [];

                if (lowStockItems.length === 0) {
                    lowStockList.append(`
                        <div class="list-group-item text-center py-3">
                            <i class="fas fa-check-circle text-success mb-2"></i>
                            <p class="mb-0 text-muted small">No low stock items</p>
                        </div>
                    `);

                    // Disable the Show All button if no low stock items
                    showAllButton.prop('disabled', true);
                    showAllButton.addClass('disabled');
                } else {
                    // Enable the Show All button if there are low stock items
                    showAllButton.prop('disabled', false);
                    showAllButton.removeClass('disabled');

                    // Collect unique categories from ALL items first
                    $.each(lowStockItems, function(index, item) {
                        if (item.category && categories.indexOf(item.category) === -1) {
                            categories.push(item.category);
                        }
                    });

                    // Display only the first 4 items in the sidebar
                    $.each(lowStockItems.slice(0, 4), function(index, item) {
                        lowStockList.append(`
                            <div class="list-group-item py-2">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 truncate-text" title="${item.name}">${item.name}</h6>
                                        <small class="text-muted d-block small">Code: ${item.code}</small>
                                    </div>
                                    <span class="badge bg-danger ms-2">${item.quantity} left</span>
                                </div>
                            </div>
                        `);
                    });

                    // Populate all items in the modal table
                    $.each(lowStockItems, function(index, item) {
                        lowStockModalBody.append(`
                            <tr data-category="${item.category}">
                                <td>${item.name}</td>
                                <td>${item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'N/A'}</td>
                                <td>
                                    <span class="badge bg-warning">${item.quantity} left</span>
                                </td>
                            </tr>
                        `);
                    });

                    // Add category options to filter
                    categories.sort().forEach(function(category) {
                        if (category) {
                            categoryFilter.append(`
                                <option value="${category}">${category.charAt(0).toUpperCase() + category.slice(1)}</option>
                            `);
                        }
                    });
                }

                // Initialize tooltips on truncated text
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('.truncate-text[title]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Function to update summary cards
            function updateSummaryCards() {
                $.ajax({
                    url: '{{ route("walk-in.inventory.summary") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;

                            $('#total-inventory-count').text(data.total_plants);
                            $('#low-stock-count').text(data.low_stock_count);
                            $('#today-sales-count').text(data.today_sales);
                            $('#total-revenue').text('₱' + data.monthly_revenue);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);

                        // Set default values on error
                        $('#total-inventory-count').text('N/A');
                        $('#low-stock-count').text('N/A');
                        $('#today-sales-count').text('N/A');
                        $('#total-revenue').text('N/A');
                    }
                });
            }

            // Function to show toast messages
            function showToast(title, message, type) {
                var toastId = 'toast-' + Date.now();
                var toastHTML = `
                    <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                <strong>${title}:</strong> ${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;

                $('#toast-container').append(toastHTML);
                var toastElement = document.getElementById(toastId);
                var toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
                toast.show();

                // Remove toast from DOM after it's hidden
                $(toastElement).on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Sales Records functionality
            let currentPage = 1;

            // Records button click handler
            $('#records-btn').on('click', function() {
                loadSalesRecords();
            });

            // Filter button click handler
            $('#filter-records-btn').on('click', function() {
                currentPage = 1;
                loadSalesRecords();
            });

            // Function to load sales records
            function loadSalesRecords(page = 1) {
                currentPage = page;
                $('#no-records-message').addClass('d-none');
                $('#records-loading').removeClass('d-none');
                $('#sales-records-body').empty();
                $('#sales-records-pagination').empty();
                
                // Prepare query parameters
                const params = new URLSearchParams();
                params.append('page', page);
                
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();
                
                if (startDate) {
                    params.append('start_date', startDate);
                }
                
                if (endDate) {
                    params.append('end_date', endDate);
                }
                
                // Fetch records from server
                $.ajax({
                    url: '/walk-in/records?' + params.toString(),
                    type: 'GET',
                    success: function(response) {
                        $('#records-loading').addClass('d-none');
                        
                        if (response.success) {
                            const records = response.data.data;
                            
                            if (records.length === 0) {
                                $('#no-records-message').removeClass('d-none');
                                return;
                            }
                            
                            // Populate records table
                            records.forEach(record => {
                                const saleDate = new Date(record.sale_date);
                                const formattedDate = saleDate.toLocaleDateString() + ' ' + 
                                                    saleDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                                
                                $('#sales-records-body').append(`
                                    <tr>
                                        <td>${formattedDate}</td>
                                        <td><span class="truncate-text" title="${record.plant ? record.plant.name : 'Unknown'}">${record.plant ? record.plant.name : 'Unknown'}</span></td>
                                        <td>${record.height || 'N/A'}</td>
                                        <td>${record.spread || 'N/A'}</td>
                                        <td>${record.spacing || 'N/A'}</td>
                                        <td>${record.quantity}</td>
                                        <td>₱${parseFloat(record.price).toFixed(2)}</td>
                                        <td>₱${parseFloat(record.total_price).toFixed(2)}</td>
                                        <td><span class="truncate-text" title="${record.customer_name || 'N/A'}">${record.customer_name || 'N/A'}</span></td>
                                        <td>${record.payment_method}</td>
                                    </tr>
                                `);
                            });
                            
                            // Create pagination
                            createPagination(response.data);
                        } else {
                            $('#no-records-message').removeClass('d-none')
                                .find('p').text('Error loading records: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        $('#records-loading').addClass('d-none');
                        $('#no-records-message').removeClass('d-none')
                            .find('p').text('Error loading records. Please try again.');
                        console.error(xhr);
                    }
                });
            }
            
            // Function to create pagination links
            function createPagination(data) {
                if (data.last_page <= 1) return;
                
                const pagination = $('#sales-records-pagination');
                
                // Create pagination container
                const paginationNav = $('<nav aria-label="Sales records pagination"></nav>');
                const paginationList = $('<ul class="pagination"></ul>');
                
                // Previous page link
                const prevLi = $('<li class="page-item"></li>');
                if (data.current_page === 1) {
                    prevLi.addClass('disabled');
                } else {
                    prevLi.click(function() {
                        loadSalesRecords(data.current_page - 1);
                    });
                }
                prevLi.append('<a class="page-link" href="javascript:void(0)">Previous</a>');
                paginationList.append(prevLi);
                
                // Page number links
                for (let i = 1; i <= data.last_page; i++) {
                    const pageLi = $('<li class="page-item"></li>');
                    if (i === data.current_page) {
                        pageLi.addClass('active');
                    } else {
                        pageLi.click(function() {
                            loadSalesRecords(i);
                        });
                    }
                    pageLi.append(`<a class="page-link" href="javascript:void(0)">${i}</a>`);
                    paginationList.append(pageLi);
                }
                
                // Next page link
                const nextLi = $('<li class="page-item"></li>');
                if (data.current_page === data.last_page) {
                    nextLi.addClass('disabled');
                } else {
                    nextLi.click(function() {
                        loadSalesRecords(data.current_page + 1);
                    });
                }
                nextLi.append('<a class="page-link" href="javascript:void(0)">Next</a>');
                paginationList.append(nextLi);
                
                // Add to DOM
                paginationNav.append(paginationList);
                pagination.append(paginationNav);
            }
    });
</script>
@endsection
