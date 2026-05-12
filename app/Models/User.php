<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'contact_number',
        'company_name',
        'company_address',
        'address',
        'city',
        'province',
        'zip_code',
        'gender',
        'property_type',
        'account_type',
        'company_city',
        'company_province',
        'company_zip_code',
        'company_contact_person',
        'position',
        'company_phone_number',
        'business_type',
        'tin',
        'website_socials',
        'email',
        'password',
        'role',
        'avatar',
        'is_client',
        'page_access',
        'profile_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_client' => 'boolean',
        ];
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a client.
     * User is considered a client if they have the client role OR the is_client flag is set.
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->role === 'client' || (bool)$this->is_client;
    }

    /**
     * Check if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user has access to admin features.
     *
     * @return bool
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if the user has access to client features.
     * Note: All non-admin users are clients, plus admins have client access.
     *
     * @return bool
     */
    public function hasClientAccess(): bool
    {
        return (bool)$this->is_client || in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Check if user has access to a specific page.
     *
     * @param string $page
     * @return bool
     */
    public function hasPageAccess(string $page): bool
    {
        // Super admin has access to everything
        if ($this->role === 'super_admin') {
            return true;
        }

        // Home page is always accessible
        if ($page === 'home') {
            return true;
        }

        // Decode page access permissions
        $pageAccess = [];
        if ($this->page_access) {
            $decoded = json_decode($this->page_access, true);
            $pageAccess = is_array($decoded) ? $decoded : [];
        }

        // Check if user has access to the specific page
        return in_array($page, $pageAccess);
    }

    /**
     * Get all accessible pages for the user.
     *
     * @return array
     */
    public function getAccessiblePages(): array
    {
        // Super admin has access to everything
        if ($this->role === 'super_admin') {
            return ['home', 'dashboard', 'inventory', 'point_of_sale', 'requests', 'site_visits'];
        }

        // Decode page access permissions
        $pageAccess = ['home']; // Home is always accessible
        if ($this->page_access) {
            $decoded = json_decode($this->page_access, true);
            if (is_array($decoded)) {
                $pageAccess = array_merge($pageAccess, $decoded);
            }
        }

        return array_unique($pageAccess);
    }

    /**
     * Get required fields based on account type.
     *
     * @return array
     */
    public function getRequiredFields(): array
    {
        $baseFields = ['first_name', 'last_name', 'email', 'contact_number'];

        if ($this->account_type === 'company') {
            return array_merge($baseFields, [
                'company_name',
                'company_address',
                'company_city',
                'company_province',
                'company_zip_code',
                'company_contact_person',
                'position',
                'company_phone_number',
                'business_type',
            ]);
        }

        // Individual account
        return array_merge($baseFields, [
            'address',
            'city',
            'province',
            'zip_code',
            'property_type',
        ]);
    }

    /**
     * Calculate profile completion percentage.
     *
     * @return int
     */
    public function getProfileCompletionPercentage(): int
    {
        $requiredFields = $this->getRequiredFields();
        $totalFields = count($requiredFields);
        $completedFields = 0;

        foreach ($requiredFields as $field) {
            if (!empty($this->$field)) {
                $completedFields++;
            }
        }

        return $totalFields > 0 ? (int) round(($completedFields / $totalFields) * 100) : 0;
    }

    /**
     * Get missing required fields.
     *
     * @return array
     */
    public function getMissingFields(): array
    {
        $requiredFields = $this->getRequiredFields();
        $missingFields = [];

        $fieldLabels = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'contact_number' => 'Contact Number',
            'address' => 'Full Address',
            'city' => 'City',
            'province' => 'Province',
            'zip_code' => 'Zip Code',
            'property_type' => 'Property Type',
            'company_name' => 'Company Name',
            'company_address' => 'Company Address',
            'company_city' => 'Company City',
            'company_province' => 'Company Province',
            'company_zip_code' => 'Company Zip Code',
            'company_contact_person' => 'Contact Person Name',
            'position' => 'Position',
            'company_phone_number' => 'Company Phone Number',
            'business_type' => 'Business Type',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                $missingFields[] = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
            }
        }

        return $missingFields;
    }

    /**
     * Check if profile is complete.
     *
     * @return bool
     */
    public function isProfileComplete(): bool
    {
        return $this->getProfileCompletionPercentage() === 100;
    }
}