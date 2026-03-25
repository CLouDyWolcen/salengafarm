<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Site Visits - Salenga Farm</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('tree-leaf.ico')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/sidebar.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Add 15px padding to match other pages */
        .dashboard-flex .main-content {
            padding-left: 15px !important;
            padding-right: 15px !important;
            padding-top: 15px !important;
        }
        
        /* Force Leaflet map controls to have lower z-index than notifications */
        .leaflet-control-zoom,
        .leaflet-control-container,
        .leaflet-top,
        .leaflet-bottom,
        .leaflet-left,
        .leaflet-right,
        .leaflet-control {
            z-index: 500 !important;
        }
        
        /* Ensure notification dropdown is always on top */
        .notification-dropdown {
            z-index: 99999 !important;
        }
    </style>
</head>
<body class="bg-light">
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
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="color: #2d5530; font-weight: 600;">Site Visits Management</h2>
                <?php if(auth()->user()->role !== 'super_admin'): ?>
                <a href="<?php echo e(route('site-visits.create')); ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Add New Site Visit
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marked-alt me-2 text-success"></i>Site Visits Map
                    </h5>
                    <div class="btn-group">
                        <button id="toggleMapTypeBtn" class="btn btn-sm btn-outline-success" title="Toggle Satellite View">
                            <i class="fas fa-satellite"></i>
                        </button>
                        <button id="fullscreenMapBtn" class="btn btn-sm btn-outline-success" title="Fullscreen">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" style="position: relative;">
                    <div id="map" style="height: 400px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        <div class="text-center text-muted">
                            <i class="fas fa-map-marked-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                            <h5>Google Maps Integration</h5>
                            <p>To enable the interactive map, please add your Google Maps API key.</p>
                            <small>Replace 'YOUR_GOOGLE_MAPS_API_KEY' in the script tag below with your actual API key.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Visits List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-success"></i>All Site Visits
                    </h5>
                </div>
                <div class="card-body">
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
                                            <?php echo e(\Illuminate\Support\Str::limit($visit->location, 30)); ?>

                                        </td>
                                        <td><?php echo e(\Carbon\Carbon::parse($visit->visit_date)->format('M d, Y')); ?></td>
                                        <td>
                                            <?php if($visit->status === 'completed'): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php elseif($visit->status === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Follow Up</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($visit->site_inspector); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('site-visits.show', $visit)); ?>" 
                                                   class="btn btn-outline-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if(auth()->user()->role !== 'super_admin'): ?>
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
                                                <?php endif; ?>
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
                            <h4 class="mt-3 text-muted">No Site Visits Yet</h4>
                            <p class="text-muted">Start by adding your first site visit!</p>
                            <?php if(auth()->user()->role !== 'super_admin'): ?>
                            <a href="<?php echo e(route('site-visits.create')); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Add Site Visit
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Visit Details Modal -->
    <div class="modal fade" id="visitModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Site Visit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="visitModalBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('js/alerts.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/push-notifications.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Leaflet Maps API -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map;
let markers = [];
let streetLayer;
let satelliteLayer;
let labelsLayer;
let currentLayer = 'street';

// Initialize the map when the page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Force Leaflet controls to have lower z-index after map loads
    setTimeout(function() {
        const leafletControls = document.querySelectorAll('.leaflet-control-zoom, .leaflet-control-container, .leaflet-top, .leaflet-bottom, .leaflet-left, .leaflet-right, .leaflet-control');
        leafletControls.forEach(function(control) {
            control.style.zIndex = '500';
        });
    }, 1000);
});

function initMap() {
    // Philippines bounds (southwest and northeast corners)
    const philippinesBounds = [
        [4.5, 116.0],  // Southwest corner (Mindanao south, western edge)
        [21.0, 127.0]  // Northeast corner (Luzon north, eastern edge)
    ];

    // Initialize Leaflet map centered on Philippines
    map = L.map('map', {
        center: [12.8797, 121.7740], // Center of Philippines
        zoom: 6,
        minZoom: 6,  // Prevent zooming out too far
        maxZoom: 18, // Allow detailed zoom
        maxBounds: philippinesBounds, // Restrict panning to Philippines
        maxBoundsViscosity: 1.0 // Make bounds solid (can't drag outside)
    });

    // Define street layer (OpenStreetMap)
    streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    });

    // Define satellite layer (Esri World Imagery)
    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '© Esri, Maxar, Earthstar Geographics'
    });

    // Define labels layer (transparent overlay with place names, roads, etc.)
    // Same as street map labels but will be inverted for satellite view
    labelsLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© CartoDB',
        pane: 'labels'
    });
    
    // Create a custom pane for labels with higher z-index
    map.createPane('labels');
    map.getPane('labels').style.zIndex = 650;
    map.getPane('labels').style.pointerEvents = 'none';
    
    // Add custom CSS to invert label colors (black to white) with thin black outline
    const style = document.createElement('style');
    style.textContent = `
        .leaflet-labels-pane {
            filter: invert(1) drop-shadow(0.5px 0.5px 0px black) drop-shadow(-0.5px -0.5px 0px black);
        }
    `;
    document.head.appendChild(style);

    // Add default street layer
    streetLayer.addTo(map);

    // Load site visits data
    loadSiteVisits();

    // Map click listener removed - use "Add New Site Visit" button instead
}

function loadSiteVisits() {
    fetch('<?php echo e(route('site-visits.data')); ?>')
        .then(response => response.json())
        .then(data => {
            data.forEach(visit => {
                addMarkerForVisit(visit);
            });
        })
        .catch(error => {
            console.error('Error loading site visits:', error);
        });
}

function addMarkerForVisit(visit) {
    // Create custom colored marker icon
    const markerColor = getMarkerColor(visit.status);
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: ${markerColor}; width: 25px; height: 25px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
        iconSize: [25, 25],
        iconAnchor: [12, 24],
        popupAnchor: [0, -24]
    });
    
    const marker = L.marker([parseFloat(visit.latitude), parseFloat(visit.longitude)], { icon: customIcon })
        .addTo(map)
        .bindPopup(`
            <div class="p-2">
                <h6 class="mb-1">${visit.client}</h6>
                <p class="mb-1 small">${visit.location}</p>
                <p class="mb-1 small">Date: ${new Date(visit.visit_date).toLocaleDateString()}</p>
                <p class="mb-2 small">Status: <span class="badge bg-${getStatusColor(visit.status)}">${visit.status}</span></p>
                <div class="btn-group btn-group-sm">
                    <a href="/site-visits/${visit.id}" class="btn btn-outline-info btn-sm">View</a>
                    <a href="/site-visits/${visit.id}/edit" class="btn btn-outline-primary btn-sm">Edit</a>
                </div>
            </div>
        `);

    markers.push(marker);
}

function getStatusColor(status) {
    switch(status) {
        case 'completed': return 'success';
        case 'pending': return 'warning';
        case 'follow_up': return 'info';
        default: return 'secondary';
    }
}

function getMarkerColor(status) {
    switch(status) {
        case 'completed': return '#28a745'; // green
        case 'pending': return '#ffc107'; // yellow/orange
        case 'follow_up': return '#17a2b8'; // blue
        default: return '#6c757d'; // gray
    }
}

// Delete functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-visit').forEach(button => {
        button.addEventListener('click', function() {
            const visitId = this.getAttribute('data-visit-id');
            const clientName = this.getAttribute('data-client');
            
            AlertSystem.confirm({
                title: 'Delete Site Visit?',
                message: `Are you sure you want to delete the site visit for ${clientName}?`,
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    fetch(`/site-visits/${visitId}`, {
                        method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        AlertSystem.alert({
                            title: 'Error',
                            message: 'Error deleting site visit: ' + data.message,
                            type: 'error'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    AlertSystem.alert({
                        title: 'Error',
                        message: 'Error deleting site visit. Please try again.',
                        type: 'error'
                    });
                });
                }
            });
        });
    });
});

// Show success/error messages
<?php if(session('success')): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
<?php endif; ?>

<?php if(session('error')): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
<?php endif; ?>

// Fullscreen map functionality
document.getElementById('fullscreenMapBtn').addEventListener('click', function() {
    const mapContainer = document.getElementById('map').parentElement.parentElement;
    const btn = this;
    const icon = btn.querySelector('i');
    
    if (!document.fullscreenElement) {
        // Enter fullscreen
        if (mapContainer.requestFullscreen) {
            mapContainer.requestFullscreen();
        } else if (mapContainer.webkitRequestFullscreen) {
            mapContainer.webkitRequestFullscreen();
        } else if (mapContainer.msRequestFullscreen) {
            mapContainer.msRequestFullscreen();
        }
        icon.classList.remove('fa-expand');
        icon.classList.add('fa-compress');
    } else {
        // Exit fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        icon.classList.remove('fa-compress');
        icon.classList.add('fa-expand');
    }
});

// Toggle between street and satellite view
document.getElementById('toggleMapTypeBtn').addEventListener('click', function() {
    const btn = this;
    const icon = btn.querySelector('i');
    
    if (currentLayer === 'street') {
        // Switch to satellite with labels
        map.removeLayer(streetLayer);
        satelliteLayer.addTo(map);
        labelsLayer.addTo(map); // Add labels on top of satellite
        currentLayer = 'satellite';
        icon.classList.remove('fa-satellite');
        icon.classList.add('fa-map');
        btn.setAttribute('title', 'Toggle Street View');
    } else {
        // Switch to street
        map.removeLayer(satelliteLayer);
        map.removeLayer(labelsLayer); // Remove labels layer
        streetLayer.addTo(map);
        currentLayer = 'street';
        icon.classList.remove('fa-map');
        icon.classList.add('fa-satellite');
        btn.setAttribute('title', 'Toggle Satellite View');
    }
});

// Listen for fullscreen changes to update button icon
document.addEventListener('fullscreenchange', function() {
    const btn = document.getElementById('fullscreenMapBtn');
    const icon = btn.querySelector('i');
    if (!document.fullscreenElement) {
        icon.classList.remove('fa-compress');
        icon.classList.add('fa-expand');
    }
});

// Add CSS for fullscreen map
const style = document.createElement('style');
style.textContent = `
    .card:fullscreen {
        display: flex;
        flex-direction: column;
        background: white;
    }
    .card:fullscreen .card-body {
        flex: 1;
        display: flex;
    }
    .card:fullscreen #map {
        height: 100% !important;
        flex: 1;
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visits/index.blade.php ENDPATH**/ ?>