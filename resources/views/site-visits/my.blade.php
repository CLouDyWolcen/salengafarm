<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Site Visits - Plant Inventory</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div id="sidebarOverlay"></div>
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <!-- Sidebar Toggle Button for Mobile -->
        <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar">
            <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
        </button>
        <div class="main-content">
            <div class="container-fluid" style="padding-top: 1rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-user-check me-2 text-success"></i>
                My Site Visits
            </h2>
            <a href="{{ route('site-visits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>All Site Visits
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-success">
                            <tr>
                                <th>Visit Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Inspector</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siteVisits as $visit)
                                <tr>
                                    <td>{{ optional($visit->visit_date)->format('M j, Y') }}</td>
                                    <td>{{ $visit->location }}</td>
                                    <td>
                                        @php($badge = $visit->status_badge_color)
                                        <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $visit->status)) }}</span>
                                    </td>
                                    <td>{{ $visit->site_inspector }}</td>
                                    <td>
                                        <a href="{{ route('site-visits.view', $visit) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">No site visits linked to your account yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
