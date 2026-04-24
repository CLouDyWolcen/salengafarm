@extends('layouts.public')

@push('styles')
<link href="{{ asset('css/client-data.css') }}?v={{ time() }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid py-3 client-data-page">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-folder-open me-2 text-success"></i>
                Client Data
            </h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
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
                                <th>Uploads Open</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siteVisits as $visit)
                                @php
                                    $isOpen = ($visit->client_data_open ?? false) || ($visit->status === 'completed');
                                    $badge = $visit->status_badge_color;
                                @endphp
                                <tr>
                                    <td>{{ optional($visit->visit_date)->format('M j, Y') }}</td>
                                    <td>{{ $visit->location ?? $visit->location_address ?? '—' }}</td>
                                    <td><span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ', $visit->status)) }}</span></td>
                                    <td>
                                        @if($isOpen)
                                            <span class="badge bg-success">Open</span>
                                        @else
                                            <span class="badge bg-secondary">Closed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('client-data.show', $visit) }}" class="btn btn-sm btn-success client-data-open-btn">
                                            <i class="fas fa-eye me-1"></i>Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">No client data available yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
