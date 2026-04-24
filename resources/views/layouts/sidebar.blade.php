@php
    use Illuminate\Support\Facades\Auth;
@endphp

<nav id="sidebarMenu" class="sidebar" style="background: #f8faf5; box-shadow: 8px 0 32px rgba(60,120,60,0.22); border-radius: 0;">
    <style>
        /* Force consistent sidebar link sizing across ALL pages - maximum specificity */
        #sidebarMenu.sidebar .nav-link.sidebar-link,
        #sidebarMenu .nav-item .nav-link.sidebar-link,
        #sidebarMenu .nav-link,
        .sidebar .nav-link,
        .sidebar .nav-item .nav-link,
        nav#sidebarMenu .nav-link,
        nav.sidebar .nav-link {
            font-size: 0.89rem !important;
            padding: 0.7rem 1.2rem !important;
            font-weight: 400 !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            line-height: 1.5 !important;
        }
    </style>
    <div class="sidebar-header">
        <a href="/" class="d-flex align-items-center justify-content-start mb-1" style="padding-left: 0;">
            <img src="{{ asset('images/salengap-modified.png') }}" alt="Salenga Logo" style="height: 28px;">
            </a>
        <span class="brand-script">Salenga Farm</span>
        </div>
    <ul class="nav flex-column mt-3" style="gap: 0.25rem; flex: 1 1 auto;">
        @php($user = Auth::user())
        @php($isAdmin = $user && method_exists($user, 'hasAdminAccess') ? $user->hasAdminAccess() : false)
        @php($isClient = $user && method_exists($user, 'isClient') ? $user->isClient() : false)
        @php($currentRoute = request()->path())
        <li class="nav-item">
            <a href="/" class="nav-link sidebar-link {{ $currentRoute == '/' ? 'active' : '' }}" title="Home">
                <i class="fas fa-house me-2 text-success"></i> <span>Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/dashboard" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="fas fa-tachometer-alt me-2 text-success"></i> <span>Dashboard</span>
            </a>
        </li>
        @if($isAdmin)
            <li class="nav-item">
                <a href="/plants" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'plants') ? 'active' : '' }}" title="Inventory">
                    <i class="fas fa-seedling me-2 text-success"></i> <span>Inventory</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ Auth::user()->role === 'super_admin' ? '/walk-in/inventory' : '/walk-in' }}" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'walk-in') ? 'active' : '' }}" title="Point-of-Sale">
                    <i class="fas fa-cash-register me-2 text-success"></i> <span>Point-of-Sale</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/requests" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'requests') ? 'active' : '' }}" title="Requests">
                    <i class="fas fa-envelope-open-text me-2 text-success"></i> <span>Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/site-visits" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'site-visits') ? 'active' : '' }}" title="Site Visits">
                    <i class="fas fa-map-marked-alt me-2 text-success"></i> <span>Site Visits</span>
                </a>
            </li>
            @if($user && $user->role === 'super_admin')
            <li class="nav-item">
                <a href="/users" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'users') ? 'active' : '' }}" title="Users">
                    <i class="fas fa-users-cog me-2 text-success"></i> <span>Users</span>
                </a>
            </li>
            @endif
        @elseif($isClient)
            <li class="nav-item">
                <a href="{{ route('client-data.index') }}" class="nav-link sidebar-link {{ str_starts_with($currentRoute, 'client-data') ? 'active' : '' }}" title="Client Data">
                    <i class="fas fa-folder-open me-2 text-success"></i> <span>Client Data</span>
                </a>
            </li>
        @endif
    </ul>
    <hr style="margin: 1.2rem 0 0.7rem 0.7rem; border-color: #e0e8d8;">
    
    <!-- Notification Bell for Admins (above profile) -->
    @if($isAdmin)
    <div class="px-3 mb-3">
        <a href="#" class="nav-link sidebar-link d-flex align-items-center notification-bell-trigger" id="sidebarNotificationBell" title="Notifications" style="padding: 0.7rem 1.2rem;">
            <i class="fas fa-bell me-2 text-success"></i>
            <span>Notifications</span>
            <span class="badge bg-danger ms-auto notification-badge" style="display: none;">0</span>
        </a>
    </div>
    @endif
    
    <!-- Sidebar Footer: Profile Card and Logout -->
    @auth
    <div class="sidebar-footer">
        <a href="/profile/edit" class="sidebar-profile-card-link" title="Profile">
            <div class="sidebar-profile-card">
                <div class="sidebar-profile-avatar">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="sidebar-profile-image">
                    @else
                        <div class="sidebar-profile-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    @endif
                </div>
                <div class="sidebar-profile-info">
                    <div class="sidebar-profile-name">{{ Auth::user()->first_name ?? Auth::user()->name ?? 'User' }}</div>
                </div>
            </div>
        </a>
        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="button" class="nav-link sidebar-link sidebar-logout-link" id="logout-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </button>
        </form>
    </div>
    @endauth
    @guest
    <div class="sidebar-footer">
        <a href="{{ route('login') }}" class="nav-link sidebar-link">Login</a>
        <a href="{{ route('register') }}" class="nav-link sidebar-link">Register</a>
    </div>
    @endguest
</nav>

<!-- Notification Dropdown (positioned outside sidebar) -->
@if($isAdmin)
<div class="notification-dropdown" id="sidebarNotificationDropdown" style="display: none; position: fixed; left: 240px; top: 50%; transform: translateY(-50%); width: 380px; z-index: 99999;">
    <div class="notification-header">
        <h6><i class="fas fa-bell me-2"></i><span class="notification-text">Notifications</span></h6>
        <div class="d-flex gap-2">
            <a href="#" class="mark-all-read" title="Mark all as read">
                <i class="fas fa-check-double"></i>
            </a>
            <a href="#" class="delete-all-notifications" title="Delete all">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </div>
    <div class="notification-list">
        <div class="no-notifications">
            <i class="fas fa-seedling"></i>
            <p>Loading...</p>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            AlertSystem.confirm({
                title: 'Logout?',
                message: 'Are you sure you want to log out?',
                confirmText: 'Yes, Logout',
                cancelText: 'Cancel',
                onConfirm: function() {
                    document.getElementById('sidebar-logout-form').submit();
                }
            });
        });
    }
});
</script>