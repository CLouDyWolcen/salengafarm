@if($siteVisits->count() > 0)
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
                @foreach($siteVisits as $visit)
                <tr>
                    <td>
                        <strong>{{ $visit->client }}</strong><br>
                        <small class="text-muted">{{ $visit->email }}</small>
                    </td>
                    <td>
                        <i class="fas fa-map-marker-alt text-success me-1"></i>
                        {{ \Illuminate\Support\Str::limit($visit->location_address ?? $visit->location ?? 'N/A', 30) }}
                    </td>
                    <td>{{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        @if($visit->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($visit->status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($visit->status === 'follow_up')
                            <span class="badge bg-info">Follow Up</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($visit->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $visit->site_inspector ?? 'N/A' }}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('site-visits.show', $visit) }}" 
                               class="btn btn-outline-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('site-visits.edit', $visit) }}" 
                               class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm delete-visit" 
                                    data-visit-id="{{ $visit->id }}"
                                    data-client="{{ $visit->client }}"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-map-marked-alt text-muted" style="font-size: 3rem;"></i>
        <h4 class="mt-3 text-muted">{{ $emptyMessage ?? 'No site visits found' }}</h4>
        @if($emptyMessage === 'No site visits yet')
            <p class="text-muted">Start by adding your first site visit!</p>
            <a href="{{ route('site-visits.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Add Site Visit
            </a>
        @endif
    </div>
@endif
