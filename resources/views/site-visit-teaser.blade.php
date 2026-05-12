@extends('layouts.public')

@section('title', 'Site Visit Management - Salenga Farm')

@push('styles')
<style>
    .teaser-hero {
        background: linear-gradient(135deg, #2a9d4e 0%, #6cbf84 100%);
        color: white;
        padding: 0.4rem 0;
        text-align: center;
        border-radius: 4px;
        margin-bottom: 0.4rem;
    }
    
    .teaser-hero h1 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.1rem;
    }
    
    .teaser-hero p {
        font-size: 0.75rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .feature-showcase {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.8rem;
        margin: 0.8rem 0;
    }
    
    .feature-card {
        background: white;
        border-radius: 4px;
        padding: 0.8rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.2s ease;
        border: 1px solid #e9ecef;
    }
    
    .feature-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }
    
    .feature-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.4rem;
        font-size: 1rem;
        color: white;
    }
    
    .feature-card h4 {
        color: #2d5530;
        margin-bottom: 0.3rem;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .feature-card p {
        color: #6c757d;
        line-height: 1.3;
        margin-bottom: 0;
        font-size: 0.75rem;
    }
    
    .unlock-section {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffc107;
        border-radius: 4px;
        padding: 0.8rem;
        text-align: center;
        margin: 0.8rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .unlock-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 193, 7, 0.1) 10px,
            rgba(255, 193, 7, 0.1) 20px
        );
        animation: shimmer 3s linear infinite;
        pointer-events: none;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%); }
        100% { transform: translateX(100%) translateY(100%); }
    }
    
    .unlock-content {
        position: relative;
        z-index: 1;
    }
    
    .unlock-icon {
        font-size: 1.5rem;
        color: #856404;
        margin-bottom: 0.3rem;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .unlock-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #856404;
        margin-bottom: 0.3rem;
    }
    
    .unlock-description {
        font-size: 0.8rem;
        color: #856404;
        margin-bottom: 0.8rem;
        line-height: 1.3;
    }
    
    .progress-container {
        background: white;
        border-radius: 3px;
        padding: 0.6rem;
        margin: 0.8rem 0;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
        font-size: 0.75rem;
    }
    
    .progress-bar-custom {
        height: 16px;
        background: #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.7rem;
        transition: width 0.5s ease;
        position: relative;
    }
    
    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: progress-shine 2s infinite;
    }
    
    @keyframes progress-shine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .missing-fields {
        background: white;
        border-radius: 3px;
        padding: 0.6rem;
        margin: 0.6rem 0;
        border-left: 2px solid #dc3545;
    }
    
    .missing-fields h6 {
        color: #dc3545;
        margin-bottom: 0.4rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .missing-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.25rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .missing-list li {
        padding: 0.25rem;
        background: #f8f9fa;
        border-radius: 2px;
        border-left: 2px solid #dc3545;
        font-size: 0.7rem;
    }
    
    .missing-list li::before {
        content: '⚠️';
        margin-right: 0.5rem;
    }
    
    .cta-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 0.8rem;
    }
    
    .btn-unlock {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 3px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(40, 167, 69, 0.3);
    }
    
    .btn-unlock:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.4);
        color: white;
    }
    
    .btn-secondary-custom {
        background: white;
        border: 1px solid #6c757d;
        color: #6c757d;
        padding: 0.5rem 1rem;
        border-radius: 3px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }
    
    .btn-secondary-custom:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-1px);
    }
    
    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.6rem;
        margin: 0.8rem 0;
    }
    
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        background: white;
        border-radius: 3px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    
    .benefit-icon {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    
    .benefit-text {
        font-weight: 500;
        color: #2d5530;
        font-size: 0.75rem;
    }
    
    @media (max-width: 768px) {
        .teaser-hero h1 {
            font-size: 1rem;
        }
        
        .teaser-hero p {
            font-size: 0.7rem;
        }
        
        .feature-showcase {
            grid-template-columns: 1fr;
            gap: 0.6rem;
        }
        
        .feature-card {
            padding: 0.6rem;
        }
        
        .unlock-section {
            padding: 0.6rem;
        }
        
        .unlock-title {
            font-size: 1rem;
        }
        
        .unlock-description {
            font-size: 0.75rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
        }
        
        .btn-unlock,
        .btn-secondary-custom {
            width: 100%;
            max-width: 200px;
            justify-content: center;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-1">
    @php
        $user = auth()->user();
        $completionPercentage = $user->getProfileCompletionPercentage();
        $missingFields = $user->getMissingFields();
    @endphp
    
    <!-- Hero Section -->
    <div class="teaser-hero">
        <div class="container">
            <h1><i class="fas fa-seedling me-1"></i>Site Visit Management</h1>
            <p>Complete your profile to unlock features</p>
        </div>
    </div>
    
    <div class="container">
        <!-- Feature Showcase -->
        <div class="feature-showcase">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4>Schedule Visits</h4>
                <p>Easily schedule and manage site visits with our expert landscaping team. Get calendar reminders and track visit history.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h4>Project Documents</h4>
                <p>Upload, share, and organize all your project documents in one secure location. Access blueprints, contracts, and progress photos.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4>Track Progress</h4>
                <p>Monitor your landscaping project progress with detailed reports, milestone tracking, and real-time updates from our team.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h4>Smart Notifications</h4>
                <p>Stay informed with automated notifications about visit schedules, project updates, and important milestones.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4>Team Collaboration</h4>
                <p>Collaborate seamlessly with our landscaping experts, share feedback, and communicate directly through the platform.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4>Mobile Access</h4>
                <p>Access your projects anywhere, anytime. Our responsive design works perfectly on all devices and screen sizes.</p>
            </div>
        </div>
        
        <!-- Unlock Section -->
        <div class="unlock-section">
            <div class="unlock-content">
                <div class="unlock-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2 class="unlock-title">Unlock Full Access</h2>
                <p class="unlock-description">
                    Complete your profile to access all site visit management features and get the most out of our professional landscaping services.
                </p>
                
                <!-- Progress Container -->
                <div class="progress-container">
                    <div class="progress-label">
                        <span><strong>Profile Completion</strong></span>
                        <span><strong>{{ $completionPercentage }}% Complete</strong></span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $completionPercentage }}%;">
                            {{ $completionPercentage }}%
                        </div>
                    </div>
                </div>
                
                @if(count($missingFields) > 0)
                <!-- Missing Fields -->
                <div class="missing-fields">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Still Needed</h6>
                    <ul class="missing-list">
                        @foreach($missingFields as $field)
                        <li>{{ $field }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Benefits Grid -->
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">Request professional site visits</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">View and manage visit schedules</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">Upload and share project documents</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">Track project progress and milestones</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">Receive notifications and updates</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="benefit-text">Access professional landscaping advice</div>
                    </div>
                </div>
                
                <!-- Call to Action Buttons -->
                <div class="cta-buttons">
                    <a href="{{ route('profile.edit') }}" class="btn-unlock">
                        <i class="fas fa-user-edit"></i>
                        Complete Profile Now
                    </a>
                    <a href="{{ route('dashboard.user') }}" class="btn-secondary-custom">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection