<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'location_address',
        'client',
        'contact_number',
        'email',
        'job_no',
        'project_code',
        'project_no',
        'location',
        'landscape_area',
        'site_inspector',
        'visit_date',
        
        'topography',
        'geotechnical_soils',
        'utilities',
        'immediate_surroundings',
        'tools_checklist',
        'additional_services',
        'client_data_checklist',
        'proposal_checklist',
        'client_data_statuses',
        'proposal_item_statuses',
        'proposal_approval',
        'client_data_open',
        'status',
        'notes',
        'terms_and_conditions',
        'design_quotation',
        'media_files',
        'physical_factors'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'physical_factors' => 'array',
        'topography' => 'array',
        'geotechnical_soils' => 'array',
        'utilities' => 'array',
        'immediate_surroundings' => 'array',
        'tools_checklist' => 'array',
        'additional_services' => 'array',
        'client_data_checklist' => 'array',
        'proposal_checklist' => 'array',
        'client_data_statuses' => 'array',
        'proposal_item_statuses' => 'array',
        'proposal_approval' => 'array',
        'media_files' => 'array',
        'client_data_open' => 'boolean'
    ];

    /**
     * Get the status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'follow_up' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Get formatted location string
     */
    public function getFormattedLocationAttribute()
    {
        return $this->location_address ?? $this->location ?? "{$this->latitude}, {$this->longitude}";
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for recent visits
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('visit_date', '>=', now()->subDays($days));
    }

    /**
     * Linked user (client) record, if any
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the site visit request that created this site visit (if any)
     */
    public function siteVisitRequest()
    {
        return $this->hasOne(SiteVisitRequest::class);
    }
}
