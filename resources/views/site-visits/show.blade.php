<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Site Visit Details - Plant Inventory</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('tree-leaf.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .info-section {
            background: #f8f9fa;
            border-left: 4px solid #198754;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
        }
        .info-section h5 {
            color: #198754;
            margin-bottom: 1rem;
        }
        #map {
            height: 400px;
            border-radius: 0.375rem;
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
        .media-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .media-item {
            position: relative;
            border-radius: 0.375rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .media-item img, .media-item video {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .media-item .media-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
            padding: 0.5rem;
            font-size: 0.875rem;
        }
        .checklist-item {
            background: #e9ecef;
            padding: 0.5rem;
            margin: 0.25rem 0;
            border-radius: 0.25rem;
            display: inline-block;
        }
        .info-row {
            margin-bottom: 0.75rem;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        /* Responsive layout to avoid content going off-screen */
        .main-content {
            margin-left: 240px;
            padding: 1rem 2rem;
            max-width: calc(100vw - 260px);
            overflow-x: hidden;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                max-width: 100vw;
                padding: 1rem;
            }
        }
        /* Constrain tables and wrap long filenames nicely */
        .info-section .table {
            table-layout: auto;
        }
        .info-section th, .info-section td {
            word-break: break-word;
            vertical-align: middle;
        }
        .table-responsive { overflow-x: auto; }
        .status-select { min-width: 140px; }
        .note-input { min-width: 220px; }
        .upload-group .form-control[type="file"] { min-width: 200px; }
        .table > :not(caption) > * > * { padding: .5rem .65rem; }
        /* Sticky table headers for long lists */
        .table thead th { position: sticky; top: 0; background: #fff; z-index: 2; }
        /* Slightly tighter info section spacing */
        .info-section { padding: .9rem; }
    </style>
</head>
<body class="bg-light">
    <div class="dashboard-flex">
        @include('layouts.sidebar')
        <div class="main-content">
            <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Desktop Layout -->
                <div class="d-none d-md-flex justify-content-between align-items-center mb-2">
                    <h2>
                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                        Site Visit Details
                        <span class="badge bg-{{ $siteVisit->status_badge_color }} status-badge ms-2">
                            {{ ucfirst(str_replace('_', ' ', $siteVisit->status)) }}
                        </span>
                    </h2>
                    <div>
                        <form action="{{ route('site-visits.update-status', $siteVisit) }}" method="POST" class="d-inline-flex align-items-center me-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm" style="width:auto; margin-right: 1.5rem;">
                                <option value="pending" {{ $siteVisit->status==='pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $siteVisit->status==='completed' ? 'selected' : '' }}>Completed</option>
                                <option value="follow_up" {{ $siteVisit->status==='follow_up' ? 'selected' : '' }}>Follow-up</option>
                            </select>
                            <div class="form-check form-check-sm" style="margin-right: 1.5rem;">
                                <input class="form-check-input" type="checkbox" id="quick_cdo" name="client_data_open" value="1" {{ $siteVisit->client_data_open ? 'checked' : '' }}>
                                <label class="form-check-label small" for="quick_cdo">Open Client Data</label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </form>
                        <a href="{{ route('site-visits.edit', $siteVisit) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('site-visits.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Layout -->
                <div class="d-md-none mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 style="font-size: 1.1rem; margin: 0;">
                            <i class="fas fa-map-marker-alt me-2 text-success"></i>
                            Site Visit Details
                            <span class="badge bg-{{ $siteVisit->status_badge_color }} d-block mt-2" style="font-size: 0.75rem; width: fit-content;">
                                {{ ucfirst(str_replace('_', ' ', $siteVisit->status)) }}
                            </span>
                        </h2>
                        <a href="{{ route('site-visits.index') }}" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; white-space: nowrap;">
                            <i class="fas fa-arrow-left" style="font-size: 0.7rem;"></i> Back to List
                        </a>
                    </div>
                    
                    <form action="{{ route('site-visits.update-status', $siteVisit) }}" method="POST">
                        @csrf
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary w-100">Update</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('site-visits.edit', $siteVisit) }}" class="btn btn-warning w-100">Edit</a>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="pending" {{ $siteVisit->status==='pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $siteVisit->status==='completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="follow_up" {{ $siteVisit->status==='follow_up' ? 'selected' : '' }}>Follow-up</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <div class="form-check d-flex align-items-center h-100">
                                    <input class="form-check-input me-2" type="checkbox" id="quick_cdo_mobile" name="client_data_open" value="1" {{ $siteVisit->client_data_open ? 'checked' : '' }}>
                                    <label class="form-check-label" for="quick_cdo_mobile" style="font-size: 0.85rem;">Open Client Data</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                </div>

                <!-- Location Map -->
                <div class="info-section">
                    <h5><i class="fas fa-map-pin me-2"></i>Location</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div id="map"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Coordinates:</div>
                                <div>{{ $siteVisit->latitude }}, {{ $siteVisit->longitude }}</div>
                            </div>
                            @if($siteVisit->location_address)
                                <div class="info-row">
                                    <div class="info-label">Address:</div>
                                    <div>{{ $siteVisit->location_address }}</div>
                                </div>
                            @endif
                            <div class="info-row">
                                <div class="info-label">Site Location:</div>
                                <div>{{ $siteVisit->location }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client & Project Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5><i class="fas fa-user me-2"></i>Client Information</h5>
                            <div class="info-row">
                                <div class="info-label">Client Name:</div>
                                <div>{{ $siteVisit->client }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Contact Number:</div>
                                <div>{{ $siteVisit->contact_number }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email:</div>
                                <div>{{ $siteVisit->email }}</div>
                            </div>
                            @if($siteVisit->job_no)
                                <div class="info-row">
                                    <div class="info-label">Job Number:</div>
                                    <div>{{ $siteVisit->job_no }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5><i class="fas fa-project-diagram me-2"></i>Project Information</h5>
                            @if($siteVisit->project_code)
                                <div class="info-row">
                                    <div class="info-label">Project Code:</div>
                                    <div>{{ $siteVisit->project_code }}</div>
                                </div>
                            @endif
                            @if($siteVisit->project_no)
                                <div class="info-row">
                                    <div class="info-label">Project Number:</div>
                                    <div>{{ $siteVisit->project_no }}</div>
                                </div>
                            @endif
                            @if($siteVisit->landscape_area)
                                <div class="info-row">
                                    <div class="info-label">Landscape Area:</div>
                                    <div>{{ $siteVisit->landscape_area }}</div>
                                </div>
                            @endif
                            <div class="info-row">
                                <div class="info-label">Site Inspector:</div>
                                <div>{{ $siteVisit->site_inspector }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Visit Date:</div>
                                <div>{{ $siteVisit->visit_date->format('F j, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scope of Work (Static Text) -->
                <div class="info-section">
                    <h5><i class="fas fa-tasks me-2"></i>Scope of Work</h5>
                    @include('site-visits._scope_of_work_text')
                </div>

                @if($siteVisit->physical_factors)
                    <div class="info-section">
                        <h4 class="text-center mb-2">Physical Factors</h4>
                        <h5><i class="fas fa-cloud-sun me-2"></i>Climate Factors</h5>
                        <div>
                            @php $hasYes_pf = false; @endphp
                            @foreach($siteVisit->physical_factors as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_pf = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_pf)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif
                            @php
                                $otherRemarks_pf = [];
                                foreach(($siteVisit->physical_factors ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_pf[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_pf) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_pf as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->physical_factors) && !empty($siteVisit->physical_factors['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->physical_factors['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->topography)
                    <div class="info-section">
                        <h5><i class="fas fa-mountain me-2"></i>Topography</h5>
                        <div>
                            @php $hasYes_top = false; @endphp
                            @foreach($siteVisit->topography as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_top = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_top)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif
                            @php
                                $otherRemarks_top = [];
                                foreach(($siteVisit->topography ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_top[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_top) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_top as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->topography) && !empty($siteVisit->topography['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->topography['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->geotechnical_soils)
                    <div class="info-section">
                        <h5><i class="fas fa-layer-group me-2"></i>Geotechnical & Soils</h5>
                        <div>
                            @php $hasYes_gs = false; @endphp
                            @foreach($siteVisit->geotechnical_soils as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_gs = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_gs)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif
                            @php
                                $otherRemarks_gs = [];
                                foreach(($siteVisit->geotechnical_soils ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_gs[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_gs) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_gs as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->geotechnical_soils) && !empty($siteVisit->geotechnical_soils['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->geotechnical_soils['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->utilities)
                    <div class="info-section">
                        <h5><i class="fas fa-plug me-2"></i>Utilities</h5>
                        <div>
                            @php $hasYes_ut = false; @endphp
                            @foreach($siteVisit->utilities as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_ut = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_ut)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif
                            @php
                                $otherRemarks_ut = [];
                                foreach(($siteVisit->utilities ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_ut[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_ut) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_ut as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->utilities) && !empty($siteVisit->utilities['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->utilities['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->immediate_surroundings)
                    <div class="info-section">
                        <h5><i class="fas fa-eye me-2"></i>Immediate Surroundings</h5>
                        <div>
                            @php $hasYes_is = false; @endphp
                            @foreach($siteVisit->immediate_surroundings as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_is = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_is)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif
                            @php
                                $otherRemarks_is = [];
                                foreach(($siteVisit->immediate_surroundings ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_is[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_is) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_is as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->immediate_surroundings) && !empty($siteVisit->immediate_surroundings['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->immediate_surroundings['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->additional_services)
                    <div class="info-section">
                        <h5><i class="fas fa-people-carry-box me-2"></i>Additional Services</h5>
                        <div>
                            @php $hasYes_as = false; @endphp
                            @foreach($siteVisit->additional_services as $key => $data)
                                @php
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $val = is_array($data) ? ($data['value'] ?? null) : null;
                                    $remarks = is_array($data) ? ($data['remarks'] ?? null) : null;
                                @endphp
                                @if($key !== 'notes' && $val === 'yes')
                                    @php $hasYes_as = true; @endphp
                                    <span class="checklist-item">{{ $label }}@if($remarks) - {{ $remarks }}@endif</span>
                                @endif
                            @endforeach
                            @if(!$hasYes_as)
                                <span class="text-muted">No items marked Yes.</span>
                            @endif

                            @php
                                $otherRemarks_as = [];
                                foreach(($siteVisit->additional_services ?? []) as $k => $d) {
                                    if ($k === 'notes') continue;
                                    $v = is_array($d) ? ($d['value'] ?? null) : null;
                                    $r = is_array($d) ? ($d['remarks'] ?? null) : null;
                                    if ($v !== 'yes' && !empty($r)) {
                                        $otherRemarks_as[] = [
                                            'label' => ucfirst(str_replace('_', ' ', $k)),
                                            'remarks' => $r,
                                        ];
                                    }
                                }
                            @endphp
                            @if(count($otherRemarks_as) > 0)
                                <div class="mt-2">
                                    <small class="text-muted d-block">Remarks on other items:</small>
                                    <ul class="mb-0">
                                        @foreach($otherRemarks_as as $or)
                                            <li><small>{{ $or['label'] }} - {{ $or['remarks'] }}</small></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(is_array($siteVisit->additional_services) && !empty($siteVisit->additional_services['notes']))
                            <div class="mt-2"><small class="text-muted">Notes: {{ $siteVisit->additional_services['notes'] }}</small></div>
                        @endif
                    </div>
                @endif

                @if($siteVisit->tools_checklist)
                    <div class="info-section">
                        <h5><i class="fas fa-toolbox me-2"></i>Tools</h5>
                        @php
                            $tl = $siteVisit->tools_checklist ?? [];
                            $categories = [
                                'safety' => 'Safety',
                                'documentation' => 'Documentation',
                                'drawing' => 'Drawing',
                            ];
                            $hasAnyTool = false;
                        @endphp
                        <div class="row">
                            @foreach($categories as $catKey => $catLabel)
                                @php
                                    $checked = [];
                                    foreach(($tl[$catKey] ?? []) as $toolKey => $val) {
                                        if($val){
                                            $checked[] = ucwords(str_replace('_', ' ', $toolKey));
                                        }
                                    }
                                @endphp
                                @if(count($checked) > 0)
                                    @php $hasAnyTool = true; @endphp
                                    <div class="col-md-4 mb-2">
                                        <strong>{{ $catLabel }}</strong>
                                        <ul class="mb-0">
                                            @foreach($checked as $t)
                                                <li>{{ $t }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if(!$hasAnyTool)
                            <span class="text-muted">No tools checked.</span>
                        @endif
                    </div>
                @endif

                <!-- Media Files -->
                @if($siteVisit->media_files && count($siteVisit->media_files) > 0)
                    <div class="info-section">
                        <h5><i class="fas fa-camera me-2"></i>Media Files</h5>
                        <div class="media-gallery">
                            @foreach($siteVisit->media_files as $index => $file)
                                <div class="media-item">
                                    @if(str_starts_with($file['type'], 'image/'))
                                        <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" 
                                             class="img-fluid" data-bs-toggle="modal" data-bs-target="#mediaModal{{ $index }}">
                                    @elseif(str_starts_with($file['type'], 'video/'))
                                        <video controls class="w-100">
                                            <source src="{{ asset('storage/' . $file['path']) }}" type="{{ $file['type'] }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                    <div class="media-overlay">
                                        {{ $file['original_name'] }}
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                @if(str_starts_with($file['type'], 'image/'))
                                    <div class="modal fade" id="mediaModal{{ $index }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $file['original_name'] }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" 
                                                         class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Notes -->
                @if($siteVisit->notes)
                    <div class="info-section">
                        <h5><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
                        <div class="bg-white p-3 border-start border-success border-3">
                            {!! nl2br(e($siteVisit->notes)) !!}
                        </div>
                    </div>
                @endif

                <!-- Client's Data Checklist -->
                <div class="info-section">
                    <h5><i class="fas fa-folder-open me-2"></i>Client's Data Checklist</h5>
                    @php
                        $clientDataItems = [
                            'land_title' => 'Land Title',
                            'sketch_plan' => 'Sketch Plan',
                            'topogrophy' => 'Topography',
                            'tree_map' => 'Tree Map',
                            'site_development_plan_sdp' => 'Site Development Plan (SDP)',
                            'master_development_plant_mdp' => 'Master Development Plan (MDP)',
                            'drone_map' => 'Drone Map',
                        ];
                        $cd = $siteVisit->client_data_checklist ?? [];
                        $cdStatus = $siteVisit->client_data_statuses ?? [];
                        $isAdmin = auth()->check() && auth()->user()->hasAdminAccess();
                        $isLinkedClient = auth()->check() && $siteVisit->user_id === auth()->id();
                        $isOpen = ($siteVisit->client_data_open ?? false) || ($siteVisit->status === 'completed');
                        $canUploadClientData = $isAdmin || ($isLinkedClient && $isOpen);
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle bg-white">
                            <thead>
                                <tr>
                                    <th style="width: 28%">Item</th>
                                    <th>Files</th>
                                    <th style="width: 22%">Upload</th>
                                    <th style="width: 18%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientDataItems as $key => $label)
                                    @php
                                        $files = $cd[$key] ?? [];
                                        $st = $cdStatus[$key]['status'] ?? 'missing';
                                        $note = $cdStatus[$key]['note'] ?? null;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $label }}</td>
                                        <td>
                                            @if(empty($files))
                                                <span class="text-muted">No files uploaded.</span>
                                            @else
                                                <ul class="mb-0">
                                                    @foreach($files as $f)
                                                        <li>
                                                            <a href="{{ asset('storage/' . $f['path']) }}" target="_blank">{{ $f['original_name'] }}</a>
                                                            <small class="text-muted">({{ $f['type'] }})</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            @if($canUploadClientData)
                                                <form action="{{ route('site-visits.client-data.upload', ['siteVisit' => $siteVisit->id, 'itemKey' => $key]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="input-group input-group-sm upload-group">
                                                        <input type="file" name="file" class="form-control" required>
                                                        <button class="btn btn-success" type="submit"><i class="fas fa-upload me-1"></i>Upload</button>
                                                    </div>
                                                </form>
                                            @else
                                                <span class="text-muted">No permission to upload.</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                @php
                                                    $badge = match($st) {
                                                        'received' => 'success',
                                                        'rejected' => 'danger',
                                                        'submitted' => 'warning',
                                                        'missing' => 'secondary',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badge }}">{{ ucfirst($st) }}</span>
                                                @if($note)
                                                    <small class="text-muted d-block">Note: {{ $note }}</small>
                                                @endif
                                            </div>
                                            @if($isAdmin)
                                                <form action="{{ route('site-visits.client-data.status', ['siteVisit' => $siteVisit->id, 'itemKey' => $key]) }}" method="POST" class="row g-2 align-items-center">
                                                    @csrf
                                                    <div class="col-auto">
                                                        <select name="status" class="form-select form-select-sm status-select" required>
                                                            <option value="received" {{ $st==='received'?'selected':'' }}>✔ Received</option>
                                                            <option value="rejected" {{ $st==='rejected'?'selected':'' }}>✘ Rejected</option>
                                                            <option value="missing" {{ $st==='missing'?'selected':'' }}>Missing</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" name="note" class="form-control form-control-sm note-input" placeholder="Add note (optional)" value="{{ $note }}">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button class="btn btn-sm btn-outline-primary" type="submit">Update</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Proposal Checklist -->
                <div class="info-section">
                    <h5><i class="fas fa-file-alt me-2"></i>Proposal Checklist</h5>
                    @php
                        $proposalItems = [
                            'concept_board' => 'Concept Board',
                            'design_service_agreement' => 'Design Service Agreement',
                            'build_service_agreement' => 'Build Service Agreement',
                            'design_quotation' => 'Design Quotation',
                            'build_quotation_rough_estimate' => 'Build Quotation: Rough Estimate',
                            'supervision_quotation' => 'Supervision Quotation',
                            'bill_of_materials_boq' => 'Bill of Materials (BOQ)',
                        ];
                        $pp = $siteVisit->proposal_checklist ?? [];
                        $ppStatus = $siteVisit->proposal_item_statuses ?? [];
                        $approval = $siteVisit->proposal_approval ?? null;
                        $isAdmin = auth()->check() && auth()->user()->hasAdminAccess();
                        $isLinkedClient = auth()->check() && $siteVisit->user_id === auth()->id();
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle bg-white">
                            <thead>
                                <tr>
                                    <th style="width: 28%">Item</th>
                                    <th>Files</th>
                                    <th style="width: 22%">Upload (Admin)</th>
                                    <th style="width: 18%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proposalItems as $key => $label)
                                    @php
                                        $files = $pp[$key] ?? [];
                                        $st = $ppStatus[$key]['status'] ?? 'pending';
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $label }}</td>
                                        <td>
                                            @if(empty($files))
                                                <span class="text-muted">No files uploaded.</span>
                                            @else
                                                <ul class="mb-0">
                                                    @foreach($files as $f)
                                                        <li>
                                                            <a href="{{ asset('storage/' . $f['path']) }}" target="_blank">{{ $f['original_name'] }}</a>
                                                            <small class="text-muted">({{ $f['type'] }})</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isAdmin)
                                                <form action="{{ route('site-visits.proposal.upload', ['siteVisit' => $siteVisit->id, 'itemKey' => $key]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="input-group input-group-sm upload-group">
                                                        <input type="file" name="file" class="form-control" required>
                                                        <button class="btn btn-success" type="submit"><i class="fas fa-upload me-1"></i>Upload</button>
                                                    </div>
                                                </form>
                                            @else
                                                <span class="text-muted">Admin upload only.</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badge = match($st) {
                                                    'uploaded' => 'warning',
                                                    'reviewed' => 'info',
                                                    'approved' => 'success',
                                                    'pending' => 'secondary',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ ucfirst($st) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <strong>Client Approval:</strong>
                        @if($approval)
                            @php $ab = ($approval['status'] === 'approved') ? 'success' : 'danger'; @endphp
                            <span class="badge bg-{{ $ab }} text-uppercase">{{ str_replace('_',' ', $approval['status']) }}</span>
                            @if(!empty($approval['comment']))
                                <small class="text-muted ms-2">“{{ $approval['comment'] }}”</small>
                            @endif
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif

                        @if($isLinkedClient)
                            <form class="mt-2" action="{{ route('site-visits.proposal.approval', $siteVisit) }}" method="POST">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Decision</label>
                                        <select name="decision" class="form-select form-select-sm" required>
                                            <option value="approved">Approve</option>
                                            <option value="changes_requested">Request changes</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Comment (optional)</label>
                                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add a comment">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary w-100" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <a href="{{ route('site-visits.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('site-visits.edit', $siteVisit) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Visit
                        </a>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>Delete Visit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/alerts.js') }}?v={{ time() }}"></script>
    <script>
        function initMap() {
            const lat = {{ $siteVisit->latitude }};
            const lng = {{ $siteVisit->longitude }};
            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const marker = L.marker([lat, lng]).addTo(map);
            const popupHtml = `
                <div>
                    <h6><strong>{{ $siteVisit->client }}</strong></h6>
                    <p><strong>Location:</strong> {{ $siteVisit->location }}</p>
                    <p><strong>Visit Date:</strong> {{ $siteVisit->visit_date->format('M j, Y') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $siteVisit->status_badge_color }}">{{ ucfirst(str_replace('_', ' ', $siteVisit->status)) }}</span></p>
                </div>
            `;
            marker.bindPopup(popupHtml).openPopup();
        }

        function confirmDelete() {
            AlertSystem.confirm({
                title: 'Delete Site Visit?',
                message: 'Are you sure you want to delete this site visit? This action cannot be undone.',
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    fetch('{{ route("site-visits.destroy", $siteVisit) }}', {
                        method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        AlertSystem.toast({
                            message: 'Site visit deleted successfully',
                            type: 'success'
                        });
                        setTimeout(() => {
                            window.location.href = '{{ route("site-visits.index") }}';
                        }, 1000);
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
                        message: 'An error occurred while deleting the site visit',
                        type: 'error'
                    });
                });
                }
            });
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', initMap);
    </script>
</body>
</html>
