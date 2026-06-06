<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an audit entry
     *
     * @param string $action Action description (e.g., "User Login", "Stock Updated")
     * @param string|null $entityType Type of entity affected (e.g., "Plant", "Sale")
     * @param int|null $entityId ID of the affected entity
     * @param array|null $oldValues Data before change
     * @param array|null $newValues Data after change
     * @return void
     */
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        try {
            // Get user information
            $user = Auth::user();
            $userEmail = $user?->email ?? request()->input('email', 'Unknown');
            $userRole = $user?->role ?? 'guest';

            // Filter sensitive data from values
            if ($oldValues) {
                $oldValues = self::filterSensitiveData($oldValues);
            }
            if ($newValues) {
                $newValues = self::filterSensitiveData($newValues);
            }

            AuditLog::create([
                'user_id' => $user?->id,
                'user_email' => $userEmail,
                'user_role' => $userRole,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the application
            \Log::error('Audit logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Log user login
     */
    public static function logLogin(string $email): void
    {
        self::log('User Login', 'User', Auth::id());
    }

    /**
     * Log failed login attempt
     */
    public static function logFailedLogin(string $email): void
    {
        self::log('Login Failed', 'User', null, null, ['email' => $email]);
    }

    /**
     * Log user logout
     */
    public static function logLogout(): void
    {
        self::log('User Logout', 'User', Auth::id());
    }

    /**
     * Log password change
     */
    public static function logPasswordChange(int $userId): void
    {
        self::log('Password Changed', 'User', $userId);
    }

    /**
     * Log user creation
     */
    public static function logUserCreated(int $userId, array $userData): void
    {
        self::log('User Created', 'User', $userId, null, $userData);
    }

    /**
     * Log user update
     */
    public static function logUserUpdated(int $userId, array $oldData, array $newData): void
    {
        self::log('User Updated', 'User', $userId, $oldData, $newData);
    }

    /**
     * Log user deletion
     */
    public static function logUserDeleted(int $userId, array $userData): void
    {
        self::log('User Deleted', 'User', $userId, $userData, null);
    }

    /**
     * Log role change
     */
    public static function logRoleChanged(int $userId, string $oldRole, string $newRole): void
    {
        self::log('Role Changed', 'User', $userId, ['role' => $oldRole], ['role' => $newRole]);
    }

    /**
     * Log plant operations
     */
    public static function logPlantCreated(int $plantId, array $plantData): void
    {
        self::log('Plant Created', 'Plant', $plantId, null, $plantData);
    }

    public static function logPlantUpdated(int $plantId, array $oldData, array $newData): void
    {
        self::log('Plant Updated', 'Plant', $plantId, $oldData, $newData);
    }

    public static function logPlantDeleted(int $plantId, array $plantData): void
    {
        self::log('Plant Deleted', 'Plant', $plantId, $plantData, null);
    }

    /**
     * Log sale operations
     */
    public static function logSaleCreated(int $saleId, array $saleData): void
    {
        self::log('Sale Created', 'Sale', $saleId, null, $saleData);
    }

    public static function logSaleUpdated(int $saleId, array $oldData, array $newData): void
    {
        self::log('Sale Updated', 'Sale', $saleId, $oldData, $newData);
    }

    public static function logSaleDeleted(int $saleId, array $saleData): void
    {
        self::log('Sale Deleted', 'Sale', $saleId, $saleData, null);
    }

    /**
     * Log request operations
     */
    public static function logRequestCreated(int $requestId, array $requestData): void
    {
        self::log('Request Created', 'PlantRequest', $requestId, null, $requestData);
    }

    public static function logRequestUpdated(int $requestId, array $oldData, array $newData): void
    {
        self::log('Request Updated', 'PlantRequest', $requestId, $oldData, $newData);
    }

    public static function logRequestDeleted(int $requestId, array $requestData): void
    {
        self::log('Request Deleted', 'PlantRequest', $requestId, $requestData, null);
    }

    public static function logResponseSent(int $requestId): void
    {
        self::log('Response Sent', 'PlantRequest', $requestId);
    }

    /**
     * Log site visit operations
     */
    public static function logSiteVisitCreated(int $siteVisitId, array $siteVisitData): void
    {
        self::log('Site Visit Created', 'SiteVisit', $siteVisitId, null, $siteVisitData);
    }

    public static function logSiteVisitUpdated(int $siteVisitId, array $oldData, array $newData): void
    {
        self::log('Site Visit Updated', 'SiteVisit', $siteVisitId, $oldData, $newData);
    }

    public static function logSiteVisitDeleted(int $siteVisitId, array $siteVisitData): void
    {
        self::log('Site Visit Deleted', 'SiteVisit', $siteVisitId, $siteVisitData, null);
    }

    public static function logDocumentUploaded(string $documentType, int $relatedId, string $filename): void
    {
        self::log('Document Uploaded', $documentType, $relatedId, null, ['filename' => $filename]);
    }

    public static function logDocumentDeleted(string $documentType, int $relatedId, string $filename): void
    {
        self::log('Document Deleted', $documentType, $relatedId, ['filename' => $filename], null);
    }

    /**
     * Log file access (download/view)
     */
    public static function logFileAccess(int $encryptedFileId, string $action, string $filename): void
    {
        self::log("File {$action}", 'EncryptedFile', $encryptedFileId, null, ['filename' => $filename]);
    }

    /**
     * Filter sensitive data from arrays
     */
    private static function filterSensitiveData(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'secret', 'api_key'];
        
        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }
}
