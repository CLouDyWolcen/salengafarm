<!DOCTYPE html>
<html lang="en">
<head>
    @php
        use Illuminate\Support\Facades\Auth;
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="icon" type="image/ico" href="{{ asset('tree-leaf.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('tree-leaf.ico') }}?v=2">
    <meta name="msapplication-TileImage" content="{{ asset('tree-leaf.ico') }}?v=2">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="{{ asset('css/inventory.css') }}?v=2" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}?v=4" rel="stylesheet">
    <link href="{{ asset('css/loading.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/push-notifications.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/button-spinner.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="bg-light inventory-page">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div style="padding-top: 0;">
                <div class="p-0">
                    <h2 class="mb-4 fs-4" style="font-size: 1.1rem; padding-top: 10px;">Plant Inventory</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search Bar -->
                    <div class="search-bar mb-4" style="padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                                <div class="mb-2">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name, code, or scientific name" style="font-size: 0.9rem; padding: 0.4rem 0.75rem;">
                            </div>
                                <div class="d-flex gap-2 mb-2">
                                    <button id="addBtn" class="btn btn-success btn-sm">Add New Plant</button>
                                <div id="bulkActionButtons" class="d-none d-inline">
                                        <button id="bulkEditBtn" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                        <button id="cancelSelectionBtn" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                        <button id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <label style="font-size: 0.9rem; margin: 0;">Category Filter</label>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" id="addCategoryBtn" class="btn btn-outline-success btn-sm" style="padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="category-icons d-flex align-items-center" style="padding: 0.7rem; gap: 0.5rem !important; justify-content: space-between;">
                                <div class="category-icon-item text-center" data-category="all">
                                    <div class="icon-circle active">
                                        <i class="fas fa-border-all"></i>
                                    </div>
                                    <span class="d-block mt-1">All</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="shrub">
                                    <img src="{{ asset('images/categories/shrub-g.png') }}" alt="Shrub" class="category-img">
                                    <span class="d-block mt-1">Shrub</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="herbs">
                                    <img src="{{ asset('images/categories/herbs-g.png') }}" alt="Herbs" class="category-img">
                                    <span class="d-block mt-1">Herbs</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="palm">
                                    <img src="{{ asset('images/categories/palm-g.png') }}" alt="Palm" class="category-img">
                                    <span class="d-block mt-1">Palm</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="tree">
                                    <img src="{{ asset('images/categories/tree-g.png') }}" alt="Tree" class="category-img">
                                    <span class="d-block mt-1">Tree</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="grass">
                                    <img src="{{ asset('images/categories/grass-g.png') }}" alt="Grass" class="category-img">
                                    <span class="d-block mt-1">Grass</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="bamboo">
                                    <img src="{{ asset('images/categories/bamboo-g.png') }}" alt="Bamboo" class="category-img">
                                    <span class="d-block mt-1">Bamboo</span>
                                </div>
                                <div class="category-icon-item text-center" data-category="fertilizer">
                                    <img src="{{ asset('images/categories/fertilizer-g.png') }}" alt="Fertilizer" class="category-img">
                                    <span class="d-block mt-1">Fertilizer</span>
                                </div>
                            </div>
                            <div style="text-align: right; margin: 0; padding: 0;">
                                <a href="#" class="text-success text-decoration-none" style="font-size: 0.875rem;">Show more ▼</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Edit Section (Initially Hidden) -->
                <div id="bulkEditSection" class="card mb-3 d-none">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Bulk Edit Selected Plants</h6>
                    </div>
                    <div class="card-body">
                        <form id="bulkEditForm" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Availability Status</label>
                                <select class="form-control" id="bulkAvailability">
                                    <option value="">No Change</option>
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="pending">Pending Restock</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unit Cost (₱)</label>
                                <input type="number" class="form-control" id="bulkPrice" step="0.01" placeholder="No Change">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cost per SQM (₱)</label>
                                <input type="number" class="form-control" id="bulkCostPerSqm" step="0.01" placeholder="No Change">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Cost per MM (₱)</label>
                                <input type="number" class="form-control" id="bulkCostPerMm" step="0.01" placeholder="No Change">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="bulkQuantity" placeholder="No Change">
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-secondary me-2" id="cancelBulkEdit">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveBulkEdit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Plants Table -->
                    <table class="table" id="plantsTable" style="font-size: 0.8rem;">
                    <thead>
                        <tr>
                            <th width="50">
                                <label class="container-checkbox">
                                    <input type="checkbox" id="selectAll">
                                    <div class="checkmark"></div>
                                </label>
                            </th>
                            <th width="50">#</th>
                            <th width="20%">Name</th>
                            <th width="10%">Code</th>
                            <th width="20%">Scientific Name</th>
                            <th width="10%">Availability</th>
                            <th width="10%">Height (mm)</th>
                            <th width="10%">Spread (mm)</th>
                            <th width="10%">Spacing (mm)</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="plantsTableBody">
                        @foreach($plants as $index => $plant)
                            <tr class="plant-row" 
                                data-id="{{ $plant->id }}"
                                data-category="{{ $plant->category }}"
                                data-height-mm="{{ $plant->height_mm }}"
                                data-spread-mm="{{ $plant->spread_mm }}"
                                data-spacing-mm="{{ $plant->spacing_mm }}"
                                data-oc="{{ $plant->oc }}"
                                data-price="{{ $plant->price }}"
                                data-cost-per-sqm="{{ $plant->cost_per_sqm }}"
                                data-pieces-per-sqm="{{ $plant->pieces_per_sqm }}"
                                data-cost-per-mm="{{ $plant->cost_per_mm }}"
                                data-quantity="{{ $plant->quantity }}"
                                data-original-index="{{ $index + 1 }}">
                                <td>
                                    <label class="container-checkbox">
                                        <input type="checkbox" class="plant-checkbox" value="{{ $plant->id }}">
                                        <div class="checkmark"></div>
                                    </label>
                                </td>
                                <td class="row-number">{{ $index + 1 }}</td>
                                <td class="text-nowrap">{{ $plant->name }}</td>
                                <td class="text-nowrap">{{ $plant->code }}</td>
                                <td>{{ $plant->scientific_name }}</td>
                                <td>{{ $plant->quantity > 0 ? 'In Stock' : 'Out of Stock' }}</td>
                                <td>{{ $plant->height_mm }}</td>
                                <td>{{ $plant->spread_mm }}</td>
                                <td>{{ $plant->spacing_mm }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-link text-primary edit-plant" data-plant-id="{{ $plant->id }}" title="Edit">
                                            <i class="fas fa-edit fa-lg"></i>
                                        </button>
                                        <button class="btn btn-link text-danger delete-plant" data-plant-id="{{ $plant->id }}" title="Delete">
                                            <i class="fas fa-trash fa-lg"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary toggle-details" data-plant-id="{{ $plant->id }}">
                                            <i class="fas fa-chevron-circle-down fa-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="details-row d-none" id="details-{{ $plant->id }}">
                                <td colspan="6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Basic Information</h6>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Common Name:</label>
                                                                <div>{{ $plant->name }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Code:</label>
                                                                <div>{{ $plant->code }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Scientific Name:</label>
                                                                <div>{{ $plant->scientific_name }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Stock Information</h6>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Unit Cost:</label>
                                                                <div>₱{{ number_format($plant->price, 2) }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Quantity:</label>
                                                                <div>{{ $plant->quantity }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Category:</label>
                                                                <div>{{ ucfirst($plant->category) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Measurements</h6>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Height (mm):</label>
                                                                <div>{{ $plant->height_mm }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Spread (mm):</label>
                                                                <div>{{ $plant->spread_mm }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Spacing (mm):</label>
                                                                <div>{{ $plant->spacing_mm }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">OC:</label>
                                                                <div>{{ $plant->oc }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="fw-bold">Cost Information</h6>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Cost per SQM:</label>
                                                                <div>₱{{ number_format($plant->cost_per_sqm, 2) }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">No. PCS per SQM:</label>
                                                                <div>{{ $plant->pieces_per_sqm }}</div>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="text-muted">Cost per MM:</label>
                                                                <div>₱{{ number_format($plant->cost_per_mm, 2) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Plant Details Modal -->
    <div class="modal fade" id="plantModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalTitle">Plant Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="plantForm">
                        @csrf
                        <!-- Basic Information Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Plant Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Code</label>
                                            <input type="text" class="form-control" id="code" name="code">
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="scientific_name" class="form-label">Scientific Name</label>
                                            <input type="text" class="form-control" id="scientific_name" name="scientific_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category</label>
                                            <select class="form-select" id="category" name="category" required>
                                            <option value="" selected disabled>Select Category</option>
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
                                </div>
                            </div>
                        </div>

                        <!-- Measurements Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-ruler me-2"></i>Measurements</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="height_mm" class="form-label">Height (mm)</label>
                                            <input type="number" class="form-control" id="height_mm" name="height_mm">
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="spread_mm" class="form-label">Spread (mm)</label>
                                            <input type="number" class="form-control" id="spread_mm" name="spread_mm">
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="spacing_mm" class="form-label">Spacing (mm)</label>
                                            <input type="number" class="form-control" id="spacing_mm" name="spacing_mm">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="oc" class="form-label">OC</label>
                                    <input type="text" class="form-control" id="oc" name="oc">
                                </div>
                            </div>
                        </div>

                        <!-- Cost & Inventory Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-coins me-2"></i>Cost & Inventory</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost_per_sqm" class="form-label">Cost per SQM (₱)</label>
                                            <input type="number" class="form-control" id="cost_per_sqm" name="cost_per_sqm" step="0.01" min="0" value="0">
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pieces_per_sqm" class="form-label">No. PCS per SQM</label>
                                            <input type="number" class="form-control" id="pieces_per_sqm" name="pieces_per_sqm" min="0" value="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Unit Cost (₱)</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cost_per_mm" class="form-label">Cost per MM (₱)</label>
                                            <input type="number" class="form-control" id="cost_per_mm" name="cost_per_mm" step="0.01" min="0" value="0">
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" min="0" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveBtn">
                        <i class="fas fa-save me-1"></i>Save Plant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-danger delete-icon"></i>
                    </div>
                    <p class="text-center fs-5">Are you sure you want to delete this plant?</p>
                    <p class="text-center text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> No, Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fas fa-trash me-1"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Bulk Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-exclamation-triangle text-danger delete-icon"></i>
                    </div>
                    <p class="text-center fs-5">Are you sure you want to delete <span id="bulkDeleteCount" class="fw-bold text-danger">0</span> selected plant(s)?</p>
                    <p class="text-center text-muted">This action cannot be undone.</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> No, Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmBulkDelete">
                        <i class="fas fa-trash me-1"></i> Yes, Delete All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/push-notifications-global.js') }}?v=fadefix{{ time() }}"></script>
    <script src="{{ asset('js/alerts.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/push-notifications.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function() {
            let selectedPlant = null;
            const modal = new bootstrap.Modal(document.getElementById('plantModal'));
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            let isEditing = false;
            let editingPlantId = null;

            // Setup sidebar toggle for mobile
            setupSidebarToggle();

            // Function to update row numbers
            function updateRowNumbers() {
                let visibleIndex = 1;
                $('.plant-row:visible').each(function() {
                    $(this).find('.row-number').text(visibleIndex++);
                });
            }

            // Initial numbering - numbers are already rendered by PHP, just update for filtered views
            // No need to call updateRowNumbers() on initial load to prevent layout shift

            // Category icon selection
            $('.category-icon-item').click(function() {
                const category = $(this).data('category');
                const isAlreadyActive = $(this).hasClass('active') && category !== 'all';
                
                // If clicking on already active category (except "All"), switch to "All"
                if (isAlreadyActive) {
                    // Reset to "All" category
                    $('.category-icon-item').removeClass('active');
                    $('.category-icon-item[data-category="all"]').addClass('active');
                    $('.icon-circle').removeClass('active');
                    $('.category-icon-item[data-category="all"]').find('.icon-circle').addClass('active');
                    $('.plant-row').show();
                } else {
                    // Update active state
                    $('.category-icon-item').removeClass('active');
                    $(this).addClass('active');
                    $('.icon-circle').removeClass('active');
                    if (category === 'all') {
                        $(this).find('.icon-circle').addClass('active');
                    }
                    
                    // Filter plants
                    if (category === 'all') {
                        $('.plant-row').show();
                    } else {
                        $('.plant-row').hide();
                        $(`.plant-row[data-category="${category}"]`).show();
                    }
                }
                updateRowNumbers();
            });

            // Search functionality - searches in Name, Code, and Scientific Name
            $('#searchInput').on('input', function() {
                const searchText = $(this).val().toLowerCase().trim();
                
                if (searchText === '') {
                    // Show all rows if search is empty
                    $('.plant-row').show();
                } else {
                    $('.plant-row').each(function() {
                        const row = $(this);
                        // Get text from Name (column 2), Code (column 3), and Scientific Name (column 4)
                        const name = row.find('td:eq(2)').text().toLowerCase().trim();
                        const code = row.find('td:eq(3)').text().toLowerCase().trim();
                        const scientificName = row.find('td:eq(4)').text().toLowerCase().trim();
                        
                        // Show row if search text is found in any of these columns
                        if (name.includes(searchText) || code.includes(searchText) || scientificName.includes(searchText)) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    });
                }
                
                updateRowNumbers(); // Update numbers after search
            });

            // Plant row selection
            $(document).on('click', '.plant-row', function() {
                $('.plant-row').removeClass('plant-selected');
                $(this).addClass('plant-selected');
                selectedPlant = {
                    id: $(this).data('id'),
                    name: $(this).find('td:eq(2)').text(),
                    code: $(this).find('td:eq(3)').text(),
                    scientific_name: $(this).find('td:eq(4)').text(),
                    height_mm: $(this).data('height-mm'),
                    spread_mm: $(this).data('spread-mm'),
                    spacing_mm: $(this).data('spacing-mm'),
                    oc: $(this).data('oc'),
                    price: $(this).data('price'),
                    quantity: $(this).data('quantity'),
                    category: $(this).data('category'),
                    cost_per_sqm: $(this).data('cost-per-sqm'),
                    pieces_per_sqm: $(this).data('pieces-per-sqm'),
                    cost_per_mm: $(this).data('cost-per-mm')
                };
                
                $('#editBtn, #deleteBtn').prop('disabled', false);
            });

            // Add this function to show notifications
            function showNotification(message, type = 'success') {
                const notification = $(`
                    <div class="notification-toast ${type}" style="display: none;">
                        <div class="notification-content">
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                            <span>${message}</span>
                        </div>
                    </div>
                `).appendTo('body');
                
                notification.fadeIn(300).delay(3000).fadeOut(300, function() {
                    $(this).remove();
                });
            }

            // Save button click handler
            $('#saveBtn').click(function(e) {
                e.preventDefault();
                
                // Clear previous error states
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.alert').remove();

                // Get form data properly
                const formData = new FormData();
                
                // Add CSRF token
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                
                // Add method if editing
                if (isEditing) {
                    formData.append('_method', 'PUT');
                }

                // Collect all form fields
                const fields = {
                    'name': '#name',
                    'code': '#code',
                    'scientific_name': '#scientific_name',
                    'category': '#category',
                    'height_mm': '#height_mm',
                    'spread_mm': '#spread_mm',
                    'spacing_mm': '#spacing_mm',
                    'oc': '#oc',
                    'price': '#price',
                    'cost_per_sqm': '#cost_per_sqm',
                    'pieces_per_sqm': '#pieces_per_sqm',
                    'cost_per_mm': '#cost_per_mm',
                    'quantity': '#quantity'
                };

                // Append all fields to formData, handling null/empty values properly
                Object.entries(fields).forEach(([key, selector]) => {
                    const $field = $(selector);
                    const value = $field.val();
                    // Handle numeric fields
                    if ($field.attr('type') === 'number') {
                        formData.append(key, value === null || value === '' ? '0' : value);
                    } else {
                        formData.append(key, value === null || value === '' ? '' : value.trim());
                    }
                });

                // Validate required fields
                if (!formData.get('name')) {
                    $('#name').addClass('is-invalid')
                        .after('<div class="invalid-feedback">Plant name is required</div>');
                    return;
                }
                
                // Validate category selection
                const categoryValue = $('#category').val();
                if (!categoryValue || categoryValue === '') {
                    Swal.fire({
                        title: 'No Category Selected',
                        text: 'You have not selected a category. Do you want to continue without a category?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Continue',
                        cancelButtonText: 'No, Select Category',
                        width: '400px',
                        padding: '1.5rem',
                        customClass: {
                            popup: 'compact-swal',
                            title: 'compact-swal-title',
                            htmlContainer: 'compact-swal-text'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // User chose to continue without category
                            formData.set('category', '');
                            submitPlantForm(formData);
                        } else {
                            // User chose not to continue, highlight the field
                            $('#category').addClass('is-invalid')
                                .after('<div class="invalid-feedback">Please select a category</div>');
                        }
                    });
                    return;
                }

                // Ensure numeric fields have valid values
                const numericFields = ['price', 'cost_per_sqm', 'cost_per_mm', 'pieces_per_sqm', 'quantity'];
                numericFields.forEach(field => {
                    const value = formData.get(field);
                    if (value === '') {
                        formData.set(field, '0');
                    }
                });

                // If category is selected, submit directly
                submitPlantForm(formData);
            });
            
            // Function to submit plant form
            function submitPlantForm(formData) {
                // Show Google Chrome-style spinner on button
                const $saveBtn = $('#saveBtn');
                const originalBtnHtml = $saveBtn.html();
                $saveBtn.html('<span class="btn-spinner"></span>Saving...');
                $saveBtn.prop('disabled', true);

                // Determine URL based on whether we're editing or adding
                const url = isEditing ? `/plants/${editingPlantId}` : '/plants';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $('#plantModal').modal('hide');
                        
                        // Use PushNotifications instead of custom notification
                        if (window.PushNotifications) {
                            window.PushNotifications.show('success', 'Plant saved successfully!', true);
                        }
                        
                        // Reload the table data without refreshing the page
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while saving the plant.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                        
                        // Reset button on error
                        $saveBtn.html(originalBtnHtml);
                        $saveBtn.prop('disabled', false);
                    },
                    complete: function() {
                        // Reset button (will reload on success anyway)
                        $saveBtn.html(originalBtnHtml);
                        $saveBtn.prop('disabled', false);
                    }
                });
            }

            // Add button click handler
            $('#addBtn').click(function() {
                isEditing = false;
                editingPlantId = null;
                selectedPlant = null;
                $('#modalTitle').text('Add New Plant');
                $('#plantForm')[0].reset();
                $('#editBtn, #deleteBtn').prop('disabled', true);
                
                // Populate category dropdown with custom categories
                populateCategoryDropdown();
                
                modal.show();
            });
            
            // Function to populate category dropdown with custom categories
            function populateCategoryDropdown() {
                const categorySelect = $('#category');
                const additionalCategories = JSON.parse(localStorage.getItem('additionalCategories') || '[]');
                
                // Remove any previously added custom categories
                categorySelect.find('option[data-custom="true"]').remove();
                
                // Add custom categories
                additionalCategories.forEach(cat => {
                    const option = $('<option>')
                        .val(cat.name.toLowerCase())
                        .text(cat.name)
                        .attr('data-custom', 'true');
                    categorySelect.append(option);
                });
            }

            // Edit button click handler
            $(document).on('click', '.edit-plant', function(e) {
                e.stopPropagation();
                    isEditing = true;
                editingPlantId = $(this).data('plant-id');
                const plantRow = $(this).closest('tr');
                
                // Get plant data from the row
                const plant = {
                    id: plantRow.data('id'),
                    name: plantRow.find('td:eq(2)').text().trim(),
                    code: plantRow.find('td:eq(3)').text().trim(),
                    scientific_name: plantRow.find('td:eq(4)').text().trim(),
                    category: plantRow.data('category'),
                    height_mm: plantRow.data('height-mm'),
                    spread_mm: plantRow.data('spread-mm'),
                    spacing_mm: plantRow.data('spacing-mm'),
                    oc: plantRow.data('oc'),
                    price: plantRow.data('price'),
                    cost_per_sqm: plantRow.data('cost-per-sqm'),
                    pieces_per_sqm: plantRow.data('pieces-per-sqm'),
                    cost_per_mm: plantRow.data('cost-per-mm'),
                    quantity: plantRow.data('quantity')
                };
                
                // Reset form and clear previous errors
                $('#plantForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                // Fill form with plant data
                Object.keys(plant).forEach(key => {
                    const $field = $(`#${key}`);
                    if ($field.length) {
                        $field.val(plant[key] || '');
                    }
                });
                
                $('#modalTitle').text('Edit Plant');
                $('#plantModal').modal('show');
            });

            // Delete button click handler
            $(document).on('click', '.delete-plant', function(e) {
                e.stopPropagation();
                const plantId = $(this).data('plant-id');
                selectedPlant = { id: plantId };
                    deleteModal.show();
            });

            // Confirm delete handler
            $('#confirmDelete').click(function() {
                if (!selectedPlant || !selectedPlant.id) return;
                
                const $btn = $(this);
                const originalBtnHtml = $btn.html();
                $btn.html('<span class="btn-spinner"></span>Deleting...');
                $btn.prop('disabled', true);

                $.ajax({
                    url: `/plants/${selectedPlant.id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        deleteModal.hide();
                        window.location.reload();
                    },
                    error: function(xhr) {
                        deleteModal.hide();
                        alert('Error deleting plant: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        $btn.html(originalBtnHtml);
                        $btn.prop('disabled', false);
                    },
                    complete: function() {
                        $btn.html(originalBtnHtml);
                        $btn.prop('disabled', false);
                    }
                });
            });

            // Toggle details
            $('.toggle-details').click(function(e) {
                e.stopPropagation(); // Prevent row selection when clicking toggle
                const plantId = $(this).data('plant-id');
                const detailsRow = $(`#details-${plantId}`);
                
                // Toggle the details row
                detailsRow.toggleClass('d-none');
                
                // Toggle the icon
                const icon = $(this).find('i');
                if (detailsRow.hasClass('d-none')) {
                    icon.removeClass('fa-chevron-circle-up').addClass('fa-chevron-circle-down');
                } else {
                    icon.removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
                }
            });

            // Checkbox and bulk actions functionality
            $('#selectAll').change(function() {
                $('.plant-checkbox').prop('checked', $(this).is(':checked'));
                updateBulkButtons();
            });

            $(document).on('change', '.plant-checkbox', function() {
                updateBulkButtons();
                // Update "select all" checkbox
                $('#selectAll').prop('checked', 
                    $('.plant-checkbox:checked').length === $('.plant-checkbox').length);
            });

            function updateBulkButtons() {
                const checkedCount = $('.plant-checkbox:checked').length;
                if (checkedCount > 0) {
                    $('#bulkActionButtons').removeClass('d-none');
                } else {
                    $('#bulkActionButtons').addClass('d-none');
                    $('#bulkEditSection').addClass('d-none');
                }
            }

            // Bulk edit functionality
            $('#bulkEditBtn').click(function() {
                $('#bulkEditSection').removeClass('d-none');
            });

            // Cancel selection button - uncheck all and hide bulk actions
            $('#cancelSelectionBtn').click(function() {
                $('.plant-checkbox').prop('checked', false);
                $('#selectAll').prop('checked', false);
                $('#bulkActionButtons').addClass('d-none');
                $('#bulkEditSection').addClass('d-none');
            });

            $('#cancelBulkEdit').click(function() {
                $('#bulkEditSection').addClass('d-none');
            });

            $('#saveBulkEdit').click(function() {
                const selectedIds = $('.plant-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                const bulkData = {
                    ids: selectedIds,
                    availability: $('#bulkAvailability').val() || null,
                    price: $('#bulkPrice').val() || null,
                    cost_per_sqm: $('#bulkCostPerSqm').val() || null,
                    cost_per_mm: $('#bulkCostPerMm').val() || null,
                    quantity: $('#bulkQuantity').val() || null
                };

                $.ajax({
                    url: '/plants/bulk-update',
                    method: 'POST',
                    data: bulkData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Error updating plants');
                    }
                });
            });

            // Bulk delete functionality
            $('#bulkDeleteBtn').click(function() {
                const selectedIds = $('.plant-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    alert('Please select plants to delete');
                    return;
                }

                // Update count in modal and show it
                $('#bulkDeleteCount').text(selectedIds.length);
                const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.show();
            });

            // Confirm bulk delete button
            $('#confirmBulkDelete').click(function() {
                const selectedIds = $('.plant-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                const $btn = $(this);
                const originalBtnHtml = $btn.html();
                $btn.html('<span class="btn-spinner"></span>Deleting...');
                $btn.prop('disabled', true);

                // Hide modal
                const bulkDeleteModal = bootstrap.Modal.getInstance(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.hide();

                // Delete each plant one by one
                let deletePromises = selectedIds.map(id => {
                    return $.ajax({
                        url: `/plants/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                });

                // Wait for all deletions to complete
                Promise.all(deletePromises)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        alert('Error deleting some plants. Please try again.');
                        console.error(error);
                        $btn.html(originalBtnHtml);
                        $btn.prop('disabled', false);
                    });
            });

            // Sidebar toggle function for mobile
            function setupSidebarToggle() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebar = document.getElementById('sidebarMenu');
                const overlay = document.getElementById('sidebarOverlay');
                
                if (!sidebarToggle || !sidebar || !overlay) return;
                
                // Toggle sidebar on button click
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
                
                // Close sidebar when clicking overlay
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
                
                // Close sidebar when clicking a link (mobile only)
                if (window.innerWidth <= 991) {
                    const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                    sidebarLinks.forEach(link => {
                        link.addEventListener('click', function() {
                            sidebar.classList.remove('active');
                            overlay.classList.remove('active');
                        });
                    });
                }
            }
        });
    </script>
    <style>
        /* Compact SweetAlert styling */
        .compact-swal {
            font-size: 0.9rem !important;
        }
        .compact-swal-title {
            font-size: 1.2rem !important;
            padding: 0.5rem 0 !important;
        }
        .compact-swal-text {
            font-size: 0.85rem !important;
            padding: 0.5rem 0 !important;
        }
        
        /* Animated Success Checkmark */
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .success-checkmark .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #4CAF50;
            background: white;
        }
        .success-checkmark .check-icon::before {
            top: 3px;
            left: -2px;
            width: 30px;
            transform-origin: 100% 50%;
            border-radius: 100px 0 0 100px;
        }
        .success-checkmark .check-icon::after {
            top: 0;
            left: 30px;
            width: 60px;
            transform-origin: 0 50%;
            border-radius: 0 100px 100px 0;
            animation: rotate-circle 4.25s ease-in;
        }
        .success-checkmark .check-icon::before,
        .success-checkmark .check-icon::after {
            content: '';
            height: 100px;
            position: absolute;
            background: white;
            transform: rotate(-45deg);
        }
        .success-checkmark .check-icon .icon-line {
            height: 5px;
            background-color: #4CAF50;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }
        .success-checkmark .check-icon .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
            animation-fill-mode: forwards;
        }
        .success-checkmark .check-icon .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
            animation-fill-mode: forwards;
        }
        .success-checkmark .check-icon .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid rgba(76, 175, 80, .5);
        }
        .success-checkmark .check-icon .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background-color: white;
        }
        @keyframes rotate-circle {
            0% { transform: rotate(-45deg); }
            5% { transform: rotate(-45deg); }
            12% { transform: rotate(-405deg); }
            100% { transform: rotate(-405deg); }
        }
        @keyframes icon-line-tip {
            0% { width: 0; left: 1px; top: 19px; }
            54% { width: 0; left: 1px; top: 19px; }
            70% { width: 50px; left: -8px; top: 37px; }
            84% { width: 17px; left: 21px; top: 48px; }
            100% { width: 25px; left: 14px; top: 45px; }
        }
        @keyframes icon-line-long {
            0% { width: 0; right: 46px; top: 54px; }
            65% { width: 0; right: 46px; top: 54px; }
            84% { width: 55px; right: 0px; top: 35px; }
            100% { width: 47px; right: 8px; top: 38px; }
        }
        
        .swal2-icon {
            width: 60px !important;
            height: 60px !important;
            margin: 1rem auto 0.5rem !important;
        }
        .swal2-icon.swal2-success {
            border-color: #28a745 !important;
        }
        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(40, 167, 69, 0.3) !important;
        }
        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #28a745 !important;
        }
        .swal2-actions {
            margin: 1rem 0 0.5rem !important;
        }
        .swal2-styled {
            font-size: 0.85rem !important;
            padding: 0.5rem 1.5rem !important;
        }
        
        /* Navigation and general text */
        .navbar-brand {
            font-size: 0.9rem;
        }

        .nav-link {
            font-size: 0.8rem;
        }

        /* Buttons */
        .btn {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }

        /* Form Elements */
        .form-control, .input-group-text {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }

        /* Tables */
        .table {
            font-size: 0.8rem;
        }

        .table th {
            font-size: 0.75rem;
        }

        /* Category text but not icons */
        .category-icon-item span {
            font-size: 0.75rem;
        }

        /* Profile and Dropdown */
        .profile-btn {
            font-size: 0.8rem;
        }

        .dropdown-item {
            font-size: 0.8rem;
            padding: 0.25rem 1rem;
        }

        /* Labels and Headers */
        label, .small {
            font-size: 0.75rem;
        }

        .card-header h5 {
            font-size: 0.9rem;
        }

        /* Plant Details */
        .plant-details td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }

        .plant-details th {
            font-size: 0.75rem;
            padding: 0.5rem;
        }

        /* Modal Elements */
        .modal-title {
            font-size: 1rem;
        }

        .modal-body {
            font-size: 0.8rem;
        }

        .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        /* Action Buttons Text */
        .action-buttons .btn {
            font-size: 0.8rem;
        }

        /* Status and Badge Text */
        .badge {
            font-size: 0.75rem;
        }

        /* Search Section */
        #searchInput::placeholder {
            font-size: 0.8rem;
        }

        /* Bulk Actions Section */
        .bulk-actions {
            font-size: 0.8rem;
        }

        /* Alert Messages */
        .alert {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        /* Spacing Adjustments */
        .mb-4 {
            margin-bottom: 1rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .p-4 {
            padding: 1.5rem !important;
        }

        /* Info Text */
        .text-muted {
            font-size: 0.75rem;
        }

        /* Keep category icons unchanged */
        .category-img {
            width: 28px;
            height: 28px;
        }

        .icon-circle {
            width: 28px;
            height: 28px;
        }

        /* List Items and Table Cells */
        .list-group-item {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }

        /* Checkbox Adjustments */
        .container-checkbox {
            transform: scale(0.9);
            margin: 0;
            position: relative;
            display: inline-block;
            width: 18px;  /* Match checkmark size */
            height: 18px; /* Match checkmark size */
            vertical-align: middle;
        }

        .checkmark {
            width: 18px;
            height: 18px;
            background-color: #fff;
            border: 2px solid #198754;
            border-radius: 4px;
            position: absolute;  /* Changed to absolute */
            cursor: pointer;
            display: block;     /* Changed to block */
            top: 0;            /* Position at the top */
            left: 0;           /* Position at the left */
        }

        /* Hide the default checkbox */
        .container-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
            margin: 0;        /* Remove any margins */
            padding: 0;       /* Remove any padding */
        }

        .container-checkbox input:checked ~ .checkmark {
            background-color: #198754;
        }

        .container-checkbox input:checked ~ .checkmark:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Add specific table cell styling for checkbox columns */
        .col-checkbox {
            width: 30px;      /* Fixed width for checkbox column */
            padding: 0.5rem;
            text-align: center;
            vertical-align: middle;
        }

        /* Table Row Text */
        .plant-row td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }

        /* Plant Name and Details */
        .plant-row .text-nowrap {
            font-size: 0.8rem;
        }

        /* Action Icons */
        .fa-lg {
            font-size: 1.2em !important;  /* Slightly smaller icons */
        }

        /* Details Section */
        .details-row td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }

        /* Bulk Edit Section */
        #bulkEditSection h6 {
            font-size: 0.9rem;
        }

        /* Card Headers in Forms */
        .card-header h6 {
            font-size: 0.9rem;
        }

        /* Form Section Headers */
        .section-header {
            font-size: 0.9rem;
        }

        /* Delete Modal Text */
        .delete-icon {
            font-size: 2rem;  /* Smaller warning icon */
        }

        .fs-5 {
            font-size: 0.9rem !important;
        }

        /* Table Header Background Color */
        .table thead th {
            background-color: #bcebbc !important;  /* Light green background */
            font-size: 0.8rem;
            padding: 0.5rem;
            font-weight: 500;
        }

        /* Ensure the background color stays on hover */
        .table thead tr:hover th {
            background-color: #bcebbc !important;
        }

        /* Modal styling */
        .modal-dialog {
            max-width: 800px;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .card {
            border: 1px solid rgba(0,0,0,.125);
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }

        .card-header h6 {
            color: #495057;
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 0.375rem;
            border-color: #dee2e6;
            padding: 0.5rem 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        
        /* Make category dropdown scrollable */
        #category {
            max-height: 200px;
            overflow-y: auto;
        }
        
        /* Style the dropdown options container */
        select#category option {
            padding: 0.5rem;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        /* Notification Toast Styling */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out;
        }

        .notification-toast.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .notification-toast.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification-content i {
            font-size: 1.25rem;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Delete badge for categories - positioned absolutely to not affect layout */
        .delete-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: white;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
            z-index: 100;
            pointer-events: none; /* Badge doesn't interfere with clicks */
        }

        .delete-badge i {
            font-size: 22px;
        }

        #additionalCategoriesList .category-icon-item {
            position: relative;
            min-width: 70px !important;
            max-width: 80px !important;
            transition: transform 0.2s;
            display: inline-flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.35rem 0.25rem !important;
            gap: 0.15rem !important;
            border-radius: 8px !important;
        }

        #additionalCategoriesList .category-icon-item.active {
            background-color: rgba(25, 135, 84, 0.1) !important;
            border: 2px solid #198754 !important;
            padding: calc(0.35rem - 2px) 0.25rem !important; /* Subtract border width from padding */
        }

        #additionalCategoriesList .category-icon-item .icon-circle,
        #additionalCategoriesList .category-icon-item .category-img {
            width: 40px !important;
            height: 40px !important;
            margin: 0 !important;
        }

        #additionalCategoriesList .category-icon-item span {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.2 !important;
            font-size: 0.75rem !important;
        }

        #additionalCategoriesList .category-icon-item:hover {
            background-color: rgba(13, 104, 50, 0.08) !important;
        }

        /* Fixed width for delete mode button to prevent layout shift */
        #deleteModeCategoryBtn {
            white-space: nowrap;
        }
    </style>

    <!-- Show More Categories Modal -->
    <div class="modal fade" id="showMoreCategoriesModal" tabindex="-1" data-bs-backdrop="false" data-bs-keyboard="false">
        <div class="modal-dialog" style="position: absolute; top: 180px; right: 20px; margin: 0; width: 650px; max-width: 650px;">
            <div class="modal-content" style="box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Additional Categories</h5>
                    <button type="button" class="btn-close btn-close-white" id="closeModalBtn"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Click on a category to filter plants</p>
                        <button type="button" id="deleteModeCategoryBtn" class="btn btn-outline-danger btn-sm" style="min-width: 130px;">
                            <i class="fas fa-trash"></i> Delete Mode
                        </button>
                    </div>
                    <div id="additionalCategoriesList" class="d-flex flex-wrap gap-3" style="max-height: 100px; overflow-y: auto; overflow-x: hidden; background: transparent !important;">
                        <!-- Additional categories will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="newCategoryName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Icon (optional)</label>
                            <input type="file" class="form-control" id="newCategoryIcon" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveCategoryBtn">Add Category</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Category Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-3">Are you sure you want to delete this category?</p>
                    <p class="text-muted" id="categoryToDelete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteCategoryBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Category management functionality
        let deleteMode = false;
        let selectedCategoryToDelete = null;
        let additionalCategories = @json($categories ?? []);

        // Add category button
        $('#addCategoryBtn').on('click', function() {
            $('#addCategoryModal').modal('show');
        });

        // Save new category
        $('#saveCategoryBtn').on('click', function() {
            const categoryName = $('#newCategoryName').val().trim();
            const iconFile = $('#newCategoryIcon')[0].files[0];
            
            if (!categoryName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please enter a category name',
                    confirmButtonColor: '#28a745'
                });
                return;
            }

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('name', categoryName);
            if (iconFile) {
                formData.append('icon', iconFile);
            }

            // Save to database via AJAX
            $.ajax({
                url: '{{ route("categories.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Add to local array
                    additionalCategories.push(response.category);
                    
                    $('#addCategoryModal').modal('hide');
                    $('#addCategoryForm')[0].reset();
                    
                    Swal.fire({
                        html: `
                            <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">Success!</h2>
                            <div style="width: 80px; height: 80px; margin: 1rem auto; position: relative;">
                                <svg style="width: 80px; height: 80px;" viewBox="0 0 52 52">
                                    <circle style="stroke: #4CAF50; stroke-width: 2; fill: none; stroke-dasharray: 166; stroke-dashoffset: 166; animation: stroke-circle 0.8s cubic-bezier(0.65, 0, 0.45, 1) forwards;" cx="26" cy="26" r="25"/>
                                    <path style="fill: none; stroke: #4CAF50; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke-check 0.4s cubic-bezier(0.65, 0, 0.45, 1) 0.9s forwards;" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                </svg>
                            </div>
                            <p style="margin-top: 1rem;">Category added successfully!</p>
                            <style>
                                @keyframes stroke-circle {
                                    100% { stroke-dashoffset: 0; }
                                }
                                @keyframes stroke-check {
                                    100% { stroke-dashoffset: 0; }
                                }
                            </style>
                        `,
                        showConfirmButton: true,
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload page to show new category
                        location.reload();
                    });
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Error adding category';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });

        // Show more button - display modal with additional categories
        $('a:contains("Show more")').on('click', function(e) {
            e.preventDefault();
            deleteMode = false; // Reset delete mode when opening modal
            renderAdditionalCategories();
            $('#showMoreCategoriesModal').modal('show');
        });

        // Render additional categories in modal
        function renderAdditionalCategories() {
            const modalBody = $('#additionalCategoriesList');
            
            // Get currently active category before clearing
            const activeCategory = $('.category-icons .category-icon-item.active').data('category');
            
            modalBody.empty();
            
            // Update delete button state with fixed width text
            if (deleteMode) {
                $('#deleteModeCategoryBtn')
                    .removeClass('btn-outline-danger')
                    .addClass('btn-danger')
                    .html('<i class="fas fa-times"></i> Cancel');
            } else {
                $('#deleteModeCategoryBtn')
                    .removeClass('btn-danger')
                    .addClass('btn-outline-danger')
                    .html('<i class="fas fa-trash"></i> Delete Mode');
            }
            
            if (additionalCategories.length === 0) {
                modalBody.html('<p class="text-muted">No additional categories yet. Click the <i class="fas fa-plus"></i> button to add one.</p>');
                $('#deleteModeCategoryBtn').prop('disabled', true);
            } else {
                $('#deleteModeCategoryBtn').prop('disabled', false);
                additionalCategories.forEach(cat => {
                    const categorySlug = cat.slug || cat.name.toLowerCase();
                    const isActive = activeCategory === categorySlug ? 'active' : '';
                    const badgeVisibility = deleteMode ? 'visible' : 'hidden';
                    const iconPath = cat.icon_path ? `/storage/${cat.icon_path}` : null;
                    const catHtml = `
                        <div class="category-icon-item text-center position-relative ${isActive}" data-category-id="${cat.id}" data-category="${categorySlug}" style="cursor: pointer;">
                            <span class="delete-badge" style="visibility: ${badgeVisibility};"><i class="fas fa-times-circle text-danger"></i></span>
                            ${iconPath ? `<img src="${iconPath}" alt="${cat.name}" class="category-img" style="width: 40px; height: 40px;">` : `<div class="icon-circle"><i class="fas fa-leaf"></i></div>`}
                            <span class="d-block mt-1">${cat.name}</span>
                        </div>
                    `;
                    modalBody.append(catHtml);
                });
            }
        }

        // Delete mode toggle button
        $(document).on('click', '#deleteModeCategoryBtn', function() {
            if (deleteMode) {
                // Cancel delete mode
                deleteMode = false;
                renderAdditionalCategories();
            } else {
                // Enter delete mode
                deleteMode = true;
                renderAdditionalCategories();
                Swal.fire({
                    icon: 'info',
                    title: 'Delete Mode Activated',
                    text: 'Click on any custom category to delete it.',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });

        // Close modal button
        $('#closeModalBtn').on('click', function() {
            $('#showMoreCategoriesModal').modal('hide');
        });

        // Handle category click in modal
        $(document).on('click', '#additionalCategoriesList .category-icon-item', function() {
            const categoryId = $(this).data('category-id');
            const categoryName = $(this).data('category');
            
            if (deleteMode) {
                // Delete the category
                const category = additionalCategories.find(c => c.id === categoryId);
                
                if (category) {
                    selectedCategoryToDelete = categoryId;
                    $('#categoryToDelete').text(category.name);
                    $('#deleteCategoryModal').modal('show');
                }
            } else {
                // Check if clicking on already active category
                const isAlreadyActive = $(this).hasClass('active');
                
                if (isAlreadyActive) {
                    // Toggle back to "All" category
                    $('#additionalCategoriesList .category-icon-item').removeClass('active');
                    $('.category-icons .category-icon-item').removeClass('active');
                    $('.category-icons .category-icon-item[data-category="all"]').addClass('active');
                    $('.icon-circle').removeClass('active');
                    $('.category-icons .category-icon-item[data-category="all"]').find('.icon-circle').addClass('active');
                    filterPlants('all');
                } else {
                    // Filter by this category - add active class to modal item
                    $('#additionalCategoriesList .category-icon-item').removeClass('active');
                    $(this).addClass('active');
                    
                    // Also update main categories if it exists there
                    $('.category-icons .category-icon-item').removeClass('active');
                    $('.category-icons .category-icon-item[data-category="' + categoryName + '"]').addClass('active');
                    
                    filterPlants(categoryName);
                }
                // Don't close the modal - keep it open
            }
        });

        // Confirm delete
        $('#confirmDeleteCategoryBtn').on('click', function() {
            if (selectedCategoryToDelete) {
                // Delete from database via AJAX
                $.ajax({
                    url: '{{ url("categories") }}/' + selectedCategoryToDelete,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove from local array
                        additionalCategories = additionalCategories.filter(c => c.id !== selectedCategoryToDelete);
                        
                        $('#deleteCategoryModal').modal('hide');
                        
                        selectedCategoryToDelete = null;
                        // Don't exit delete mode - stay in it for continuous deletion
                        renderAdditionalCategories();
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Error deleting category';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });

        // Filter plants by category
        function filterPlants(category) {
            if (category === 'all') {
                $('.plant-row').show();
            } else {
                $('.plant-row').each(function() {
                    if ($(this).data('category') === category) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            updateRowNumbers();
        }
    </script>
</body>
</html> 