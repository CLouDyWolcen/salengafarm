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
    <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/push-notifications.css')); ?>?v=<?php echo e(time()); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="<?php echo e(asset('css/responsive-fixes.css')); ?>" rel="stylesheet">
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
        
        /* Smooth tile loading - hide loading tiles */
        .leaflet-tile-container img {
            transition: opacity 0.2s ease-in-out;
        }
        
        .leaflet-tile {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }
        
        .leaflet-tile-loaded {
            opacity: 1 !important;
        }
        
        /* Hide the gray loading background */
        .leaflet-tile-container {
            background: transparent !important;
        }
        
        /* Smooth zoom animation */
        .leaflet-zoom-animated {
            transition: transform 0.25s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        /* FORCE map height on desktop - override any global styles */
        #map {
            height: 350px !important;
            min-height: 350px !important;
            width: 100% !important;
        }
        
        /* Map section row must not have height constraints */
        #map-section {
            height: auto !important;
        }
        
        /* Map section card must not have height constraints */
        #map-section .card {
            height: auto !important;
            min-height: auto !important;
        }
        
        /* Map container card-body must not constrain height */
        #map-card-body {
            padding: 0 !important;
            height: auto !important;
            min-height: 350px !important;
        }
        
        /* Responsive map height - smaller on mobile ONLY */
        @media (max-width: 768px) {
            #map {
                height: 300px !important;
                min-height: 300px !important;
            }
            
            #map-card-body {
                min-height: 300px !important;
            }
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
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="mb-0" style="color: #2d5530; font-weight: 600;">Site Visits Management</h2>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end gap-2">
            <a href="<?php echo e(route('site-visit-requests.index')); ?>" class="btn btn-info position-relative">
                <i class="fas fa-inbox me-2"></i>Site Visit Requests
                <?php
                    $pendingCount = \App\Models\SiteVisitRequest::where('status', 'pending')->count();
                ?>
                <?php if($pendingCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo e($pendingCount); ?>

                        <span class="visually-hidden">pending requests</span>
                    </span>
                <?php endif; ?>
            </a>
            <a href="<?php echo e(route('site-visits.create')); ?>" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add New Site Visit
            </a>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mb-4" id="map-section">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marked-alt me-2 text-success"></i>Site Visits Map
                    </h5>
                    <div class="d-flex gap-2">
                        <select id="mapStyleSelector" class="form-select form-select-sm" style="min-width: 180px;">
                            <option value="esri">Street - Esri (Fast)</option>
                            <option value="openstreetmap">Street - OpenStreetMap</option>
                            <option value="cartoVoyager">Street - Carto Voyager</option>
                            <option value="openTopoMap">Street - Topographic</option>
                            <option value="satellite">Satellite View</option>
                        </select>
                        <button id="fullscreenMapBtn" class="btn btn-sm btn-outline-success" title="Fullscreen">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div id="map-card-body" class="card-body p-0" style="position: relative;">
                    <div id="map" style="height: 350px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
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
                        <i class="fas fa-list me-2 text-success"></i>Site Visits
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" 
                                    data-bs-target="#all" type="button" role="tab">
                                All 
                                <span class="badge bg-secondary ms-1"><?php echo e($allSiteVisits->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" 
                                    data-bs-target="#pending" type="button" role="tab">
                                Pending 
                                <span class="badge bg-warning text-dark ms-1"><?php echo e($pendingSiteVisits->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="followup-tab" data-bs-toggle="tab" 
                                    data-bs-target="#followup" type="button" role="tab">
                                Follow-Up 
                                <span class="badge bg-info ms-1"><?php echo e($followUpSiteVisits->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" 
                                    data-bs-target="#completed" type="button" role="tab">
                                Completed 
                                <span class="badge bg-success ms-1"><?php echo e($completedSiteVisits->count()); ?></span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- All Site Visits -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <?php echo $__env->make('site-visits.partials.visits-table', ['siteVisits' => $allSiteVisits, 'emptyMessage' => 'No site visits yet'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <!-- Pending Site Visits -->
                        <div class="tab-pane fade" id="pending" role="tabpanel">
                            <?php echo $__env->make('site-visits.partials.visits-table', ['siteVisits' => $pendingSiteVisits, 'emptyMessage' => 'No pending site visits'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <!-- Follow-Up Site Visits -->
                        <div class="tab-pane fade" id="followup" role="tabpanel">
                            <?php echo $__env->make('site-visits.partials.visits-table', ['siteVisits' => $followUpSiteVisits, 'emptyMessage' => 'No follow-up site visits'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <!-- Completed Site Visits -->
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            <?php echo $__env->make('site-visits.partials.visits-table', ['siteVisits' => $completedSiteVisits, 'emptyMessage' => 'No completed site visits'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
let streetLayers = {}; // Make it global

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
        maxBoundsViscosity: 1.0, // Make bounds solid (can't drag outside)
        fadeAnimation: true,
        zoomAnimation: true,
        zoomAnimationThreshold: 4,
        markerZoomAnimation: true
    });

    // Define multiple street layer options
    streetLayers = {
        esri: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '© Esri, HERE, Garmin',
            keepBuffer: 4,
            updateWhenIdle: false,
            updateWhenZooming: false,
            updateInterval: 150
        }),
        openstreetmap: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors',
            subdomains: 'abc',
            keepBuffer: 4,
            updateWhenIdle: false,
            updateWhenZooming: false,
            updateInterval: 150
        }),
        cartoVoyager: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap, © CARTO',
            subdomains: 'abcd',
            keepBuffer: 4,
            updateWhenIdle: false,
            updateWhenZooming: false,
            updateInterval: 150
        }),
        openTopoMap: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom: 17,
            attribution: '© OpenStreetMap, © OpenTopoMap',
            subdomains: 'abc',
            keepBuffer: 4,
            updateWhenIdle: false,
            updateWhenZooming: false,
            updateInterval: 150
        })
    };
    
    // Set default street layer (Esri)
    streetLayer = streetLayers.esri;

    // Define satellite layer (Esri World Imagery)
    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '© Esri, Maxar, Earthstar Geographics',
        keepBuffer: 4,
        updateWhenIdle: false,
        updateWhenZooming: false,
        updateInterval: 150
    });

    // Define labels layer (for satellite view)
    labelsLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: '© Esri',
        pane: 'labels',
        keepBuffer: 4,
        updateWhenIdle: false,
        updateWhenZooming: false,
        updateInterval: 150
    });
    
    // Create a custom pane for labels with higher z-index
    map.createPane('labels');
    map.getPane('labels').style.zIndex = 650;
    map.getPane('labels').style.pointerEvents = 'none';

    // Add default street layer
    streetLayer.addTo(map);
    
    // Add subtle loading indicator
    let loadingTiles = 0;
    const loadingIndicator = document.createElement('div');
    loadingIndicator.style.cssText = `
        position: absolute;
        top: 10px;
        right: 50px;
        background: rgba(40, 167, 69, 0.9);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 1000;
        display: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    `;
    loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading map...';
    document.getElementById('map').appendChild(loadingIndicator);
    
    // Track tile loading
    map.on('tileloadstart', function() {
        loadingTiles++;
        if (loadingTiles > 0) {
            loadingIndicator.style.display = 'block';
        }
    });
    
    map.on('tileload tileerror', function() {
        loadingTiles--;
        if (loadingTiles <= 0) {
            loadingTiles = 0;
            loadingIndicator.style.display = 'none';
        }
    });

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
        
        // Fix map rendering in fullscreen
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
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
        
        // Fix map rendering after exiting fullscreen
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
});

// Map style selector (includes satellite view)
document.getElementById('mapStyleSelector').addEventListener('change', function() {
    const selectedStyle = this.value;
    
    // Show loading indicator
    const loadingIndicator = document.querySelector('#map div[style*="Loading map"]');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
    }
    
    if (selectedStyle === 'satellite') {
        // Switch to satellite view
        if (currentLayer === 'street') {
            map.removeLayer(streetLayer);
        }
        satelliteLayer.addTo(map);
        labelsLayer.addTo(map);
        currentLayer = 'satellite';
    } else {
        // Switch to street view with selected style
        if (currentLayer === 'satellite') {
            map.removeLayer(satelliteLayer);
            map.removeLayer(labelsLayer);
        } else {
            map.removeLayer(streetLayer);
        }
        streetLayer = streetLayers[selectedStyle];
        streetLayer.addTo(map);
        currentLayer = 'street';
    }
    
    // Force map to refresh
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
});

// Listen for fullscreen changes to update button icon and fix map rendering
document.addEventListener('fullscreenchange', function() {
    const btn = document.getElementById('fullscreenMapBtn');
    const icon = btn.querySelector('i');
    if (!document.fullscreenElement) {
        icon.classList.remove('fa-compress');
        icon.classList.add('fa-expand');
    }
    // Fix map rendering on fullscreen change
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
});

// Add CSS for fullscreen map
const style = document.createElement('style');
style.textContent = `
    .card:fullscreen {
        display: flex;
        flex-direction: column;
        background: white;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card:fullscreen .card-header {
        flex-shrink: 0;
    }
    .card:fullscreen .card-body {
        flex: 1;
        display: flex;
        width: 100% !important;
        height: 100% !important;
        padding: 0 !important;
    }
    .card:fullscreen #map {
        height: 100% !important;
        width: 100% !important;
        flex: 1;
    }
`;
document.head.appendChild(style);

// Sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebarMenu');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar && overlay) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
});
</script>
</body>
</html>
<?php /**PATH C:\CODING\my_Inventory\resources\views/site-visits/index.blade.php ENDPATH**/ ?>