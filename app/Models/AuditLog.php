<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    // Only created_at, no updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'user_email',
        'user_role',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Prevent updates - audit logs are immutable
     */
    public function save(array $options = [])
    {
        // Only allow creation, not updates
        if ($this->exists) {
            throw new \RuntimeException('Audit logs cannot be modified once created.');
        }
        
        return parent::save($options);
    }

    /**
     * Prevent deletion - audit logs are immutable
     */
    public function delete()
    {
        throw new \RuntimeException('Audit logs cannot be deleted.');
    }

    /**
     * Prevent force deletion - audit logs are immutable
     */
    public function forceDelete()
    {
        throw new \RuntimeException('Audit logs cannot be deleted.');
    }

    /**
     * Relationship to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a human-readable description of the changes
     */
    public function getChangesDescription(): string
    {
        if (!$this->old_values || !$this->new_values) {
            return $this->action;
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? 'N/A';
            if ($oldValue != $newValue) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . ": {$oldValue} → {$newValue}";
            }
        }

        return implode(', ', $changes);
    }

    /**
     * Check if this is a critical action
     */
    public function isCritical(): bool
    {
        $criticalActions = [
            'User Deleted',
            'Role Changed',
            'Sale Deleted',
            'Plant Deleted',
            'Request Deleted',
            'Site Visit Deleted',
        ];

        return in_array($this->action, $criticalActions);
    }

    /**
     * Check if this is a failed login
     */
    public function isFailedLogin(): bool
    {
        return $this->action === 'Login Failed';
    }

    /**
     * Get color class based on action type
     */
    public function getColorClass(): string
    {
        if ($this->isCritical()) {
            return 'danger'; // Red
        }

        if ($this->isFailedLogin()) {
            return 'warning'; // Yellow
        }

        if (str_contains($this->action, 'Created') || str_contains($this->action, 'Login')) {
            return 'success'; // Green
        }

        return 'info'; // Blue
    }
}
