# Design Document: Questionnaire Enhancements

## Overview

This design document outlines the technical approach for implementing feedback and suggestions received from 28 system evaluators. The enhancements are organized into three priority tiers: High Priority (security and reliability), Medium Priority (system improvements), and Low Priority (AI-based innovations). The design focuses on extending the existing Laravel-based Comprehensive Plant Inventory and Site Visit Management System while maintaining backward compatibility and system stability.

The implementation will be phased to address critical security concerns first, followed by operational improvements, and finally innovative AI features. Each enhancement is designed to integrate seamlessly with the existing system architecture.

## Architecture

### System Context

The existing system is built on:
- **Framework**: Laravel (PHP)
- **Database**: MySQL/MariaDB
- **Frontend**: Blade templates with Bootstrap
- **Authentication**: Laravel's built-in authentication
- **File Storage**: Local filesystem with Laravel Storage facade
- **Logging**: Laravel's logging system (already implemented)

### Architectural Additions

The enhancements will add the following architectural components:

1. **Security Layer**
   - MFA Service: Handles multi-factor authentication logic
   - Encryption Service: Manages file encryption/decryption
   - Audit Service: Enhanced logging beyond Laravel's default logs

2. **Automation Layer**
   - Backup Scheduler: Automated backup execution and management
   - Log Archiver: Monthly log archival process

3. **Role Management Layer**
   - Dynamic Permission Manager: Flexible role and permission assignment
   - Permission Cache: Performance optimization for permission checks

4. **AI Services Layer** (Future)
   - Recommendation Engine: Plant recommendation logic
   - Image Analysis Service: Disease detection from photos
   - Forecasting Service: Inventory prediction algorithms
   - Scheduling Optimizer: Site visit optimization

### Integration Points

- **Existing Authentication**: MFA will extend Laravel's authentication middleware
- **Existing File Upload**: Encryption will wrap existing file storage operations
- **Existing Logging**: Audit trail will supplement existing Laravel logs
- **Existing Roles**: Dynamic role manager will replace hardcoded role checks
- **Existing Reports**: Enhanced validation will improve report accuracy

## Components and Interfaces

### 1. Multi-Factor Authentication (MFA) Component

**MfaService**
```php
class MfaService
{
    public function enableMfa(User $user, string $method): MfaSetup
    public function generateCode(User $user): string
    public function verifyCode(User $user, string $code): bool
    public function sendCode(User $user): void
    public function disableMfa(User $user): void
    public function getRecoveryCodes(User $user): array
    public function verifyRecoveryCode(User $user, string $code): bool
}
```

**MfaMiddleware**
```php
class MfaMiddleware
{
    public function handle(Request $request, Closure $next): Response
}
```

**Database Tables**
- `user_mfa_settings`: Stores MFA configuration per user
  - `user_id`, `method` (sms/email/app), `secret`, `enabled`, `backup_codes`
- `mfa_attempts`: Tracks failed MFA attempts
  - `user_id`, `ip_address`, `attempted_at`, `success`

### 2. Enhanced Audit Trail Component

**AuditService**
```php
class AuditService
{
    public function logPasswordChange(User $user, string $ipAddress): void
    public function logStockUpdate(int $itemId, $oldValue, $newValue, User $user): void
    public function logAccountModification(User $target, array $changes, User $modifier): void
    public function logDocumentAccess(int $documentId, string $action, User $user): void
    public function archiveMonthlyLogs(int $year, int $month): void
    public function searchLogs(array $criteria): Collection
}
```

**Database Tables**
- `audit_logs`: Main audit trail table
  - `id`, `user_id`, `action_type`, `entity_type`, `entity_id`, `old_value`, `new_value`, `ip_address`, `created_at`
- `archived_audit_logs`: Monthly archived logs
  - Same structure as `audit_logs` with additional `archive_date` field

### 3. Automated Backup Component

**BackupService**
```php
class BackupService
{
    public function createBackup(): Backup
    public function verifyBackup(Backup $backup): bool
    public function scheduleBackup(string $frequency, string $time): void
    public function restoreBackup(Backup $backup): bool
    public function deleteOldBackups(int $keepCount): void
    public function getBackupList(): Collection
    public function notifyBackupStatus(Backup $backup, bool $success): void
}
```

**BackupScheduler** (Laravel Command)
```php
class BackupScheduler extends Command
{
    protected $signature = 'backup:run';
    public function handle(BackupService $service): void
}
```

**Database Tables**
- `backups`: Backup metadata
  - `id`, `filename`, `size`, `type` (manual/scheduled), `status`, `verified_at`, `created_at`

### 4. File Encryption Component

**EncryptionService**
```php
class EncryptionService
{
    public function encryptFile(UploadedFile $file): EncryptedFile
    public function decryptFile(string $encryptedPath): string
    public function deleteEncryptedFile(string $encryptedPath): void
    public function rotateEncryptionKeys(): void
}
```

**ConfidentialDocumentPolicy**
```php
class ConfidentialDocumentPolicy
{
    public function view(User $user, Document $document): bool
    public function download(User $user, Document $document): bool
    public function delete(User $user, Document $document): bool
}
```

**Database Tables**
- `encrypted_files`: Encrypted file metadata
  - `id`, `original_name`, `encrypted_path`, `encryption_key_id`, `file_type`, `uploaded_by`, `created_at`

### 5. Hosting Environment Configuration

**EnvironmentValidator**
```php
class EnvironmentValidator
{
    public function validateEmailConfiguration(): ValidationResult
    public function validateFileUploadConfiguration(): ValidationResult
    public function validatePdfGenerationConfiguration(): ValidationResult
    public function validatePhpExtensions(): ValidationResult
    public function validatePermissions(): ValidationResult
    public function generateDiagnosticReport(): array
}
```

**Configuration Files**
- `.env.production`: Production-specific environment variables
- `config/hosting.php`: Hosting environment requirements and checks

### 6. Flexible Role Management Component

**RoleManager**
```php
class RoleManager
{
    public function createRole(string $name, array $permissions): Role
    public function updateRolePermissions(Role $role, array $permissions): void
    public function deleteRole(Role $role): void
    public function assignRoleToUser(User $user, Role $role): void
    public function removeRoleFromUser(User $user, Role $role): void
    public function getUserPermissions(User $user): Collection
    public function checkPermission(User $user, string $permission): bool
}
```

**PermissionMiddleware**
```php
class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
}
```

**Database Tables**
- `roles`: Dynamic roles
  - `id`, `name`, `description`, `is_system_role`, `created_at`, `updated_at`
- `permissions`: Available permissions
  - `id`, `name`, `module`, `description`, `created_at`
- `role_permissions`: Many-to-many relationship
  - `role_id`, `permission_id`
- `user_roles`: Many-to-many relationship
  - `user_id`, `role_id`, `assigned_at`, `assigned_by`

### 7. Data Validation Component

**ValidationService**
```php
class ValidationService
{
    public function validatePlantData(array $data): ValidationResult
    public function validateNumericRange($value, $min, $max): bool
    public function validateDateLogic(array $dates): ValidationResult
    public function detectDuplicates(string $model, array $criteria): Collection
    public function validateImportData(array $rows): ValidationResult
}
```

**Custom Validation Rules**
```php
class LogicalDateRule implements Rule
class UniqueWithConfirmationRule implements Rule
class NumericRangeRule implements Rule
```

### 8. Enhanced Reporting Component

**ReportService**
```php
class ReportService
{
    public function generateInventoryReport(array $filters): Report
    public function generateSiteVisitReport(array $filters): Report
    public function generateUserActivityReport(array $filters): Report
    public function exportReport(Report $report, string $format): string
    public function validateReportData(Report $report): bool
}
```

**PdfGenerator** (Enhanced)
```php
class PdfGenerator
{
    public function generateFromView(string $view, array $data): string
    public function optimizeForProduction(): self
    public function setMemoryLimit(int $megabytes): self
}
```

### 9. AI Recommendation Engine (Future)

**PlantRecommendationService**
```php
class PlantRecommendationService
{
    public function getRecommendations(array $siteConditions, array $preferences): Collection
    public function calculateSuitabilityScore(Plant $plant, array $conditions): float
    public function explainRecommendation(Plant $plant, array $conditions): string
    public function trackRecommendationOutcome(int $recommendationId, bool $success): void
}
```

**Database Tables**
- `plant_recommendations`: Recommendation history
  - `id`, `user_id`, `plant_id`, `site_conditions`, `suitability_score`, `accepted`, `outcome`, `created_at`

### 10. AI Disease Detection Service (Future)

**DiseaseDetectionService**
```php
class DiseaseDetectionService
{
    public function analyzeImage(UploadedFile $image): DiseaseAnalysis
    public function identifyDisease(array $imageFeatures): Disease
    public function getTreatmentRecommendations(Disease $disease): Collection
    public function storeAnalysisResult(DiseaseAnalysis $analysis): void
}
```

**Database Tables**
- `disease_analyses`: Analysis history
  - `id`, `user_id`, `plant_id`, `image_path`, `detected_disease`, `confidence_score`, `treatment_recommended`, `created_at`

### 11. AI Forecasting Service (Future)

**InventoryForecastingService**
```php
class InventoryForecastingService
{
    public function generateForecast(int $itemId, int $days): Forecast
    public function analyzeConsumptionPatterns(int $itemId): ConsumptionPattern
    public function detectAnomalies(int $itemId): Collection
    public function adjustForecastModel(int $itemId, array $actualData): void
    public function getAccuracyMetrics(int $itemId): array
}
```

**Database Tables**
- `inventory_forecasts`: Forecast data
  - `id`, `item_id`, `forecast_date`, `predicted_quantity`, `actual_quantity`, `accuracy_score`, `created_at`

### 12. AI Scheduling Optimizer (Future)

**SiteVisitSchedulingService**
```php
class SiteVisitSchedulingService
{
    public function optimizeSchedule(array $pendingVisits): Schedule
    public function recommendVisitDate(int $siteId): Carbon
    public function calculateRouteEfficiency(array $visits): float
    public function considerWeatherForecast(Carbon $date): bool
    public function prioritizeUrgentVisits(Collection $visits): Collection
}
```

**Database Tables**
- `visit_schedules`: Optimized schedules
  - `id`, `site_id`, `recommended_date`, `priority`, `reasoning`, `accepted`, `actual_date`, `created_at`

## Data Models

### MFA Models

**UserMfaSetting**
```php
class UserMfaSetting extends Model
{
    protected $fillable = ['user_id', 'method', 'secret', 'enabled', 'backup_codes'];
    protected $casts = ['enabled' => 'boolean', 'backup_codes' => 'array'];
    
    public function user(): BelongsTo
    public function isEnabled(): bool
    public function verifyCode(string $code): bool
}
```

### Audit Models

**AuditLog**
```php
class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action_type', 'entity_type', 'entity_id', 'old_value', 'new_value', 'ip_address'];
    protected $casts = ['old_value' => 'array', 'new_value' => 'array'];
    
    public function user(): BelongsTo
    public static function search(array $criteria): Builder
}
```

**ArchivedAuditLog**
```php
class ArchivedAuditLog extends Model
{
    protected $fillable = ['user_id', 'action_type', 'entity_type', 'entity_id', 'old_value', 'new_value', 'ip_address', 'archive_date', 'created_at'];
    protected $casts = ['old_value' => 'array', 'new_value' => 'array', 'archive_date' => 'date'];
}
```

### Backup Models

**Backup**
```php
class Backup extends Model
{
    protected $fillable = ['filename', 'size', 'type', 'status', 'verified_at'];
    protected $casts = ['verified_at' => 'datetime'];
    
    public function isVerified(): bool
    public function getPath(): string
    public function getSizeInMB(): float
}
```

### Encryption Models

**EncryptedFile**
```php
class EncryptedFile extends Model
{
    protected $fillable = ['original_name', 'encrypted_path', 'encryption_key_id', 'file_type', 'uploaded_by'];
    
    public function uploader(): BelongsTo
    public function decrypt(): string
    public function secureDelete(): void
}
```

### Role Management Models

**Role**
```php
class Role extends Model
{
    protected $fillable = ['name', 'description', 'is_system_role'];
    protected $casts = ['is_system_role' => 'boolean'];
    
    public function permissions(): BelongsToMany
    public function users(): BelongsToMany
    public function hasPermission(string $permission): bool
}
```

**Permission**
```php
class Permission extends Model
{
    protected $fillable = ['name', 'module', 'description'];
    
    public function roles(): BelongsToMany
}
```

### AI Models (Future)

**PlantRecommendation**
```php
class PlantRecommendation extends Model
{
    protected $fillable = ['user_id', 'plant_id', 'site_conditions', 'suitability_score', 'accepted', 'outcome'];
    protected $casts = ['site_conditions' => 'array', 'accepted' => 'boolean', 'suitability_score' => 'float'];
    
    public function user(): BelongsTo
    public function plant(): BelongsTo
}
```

**DiseaseAnalysis**
```php
class DiseaseAnalysis extends Model
{
    protected $fillable = ['user_id', 'plant_id', 'image_path', 'detected_disease', 'confidence_score', 'treatment_recommended'];
    protected $casts = ['confidence_score' => 'float'];
    
    public function user(): BelongsTo
    public function plant(): BelongsTo
}
```

**InventoryForecast**
```php
class InventoryForecast extends Model
{
    protected $fillable = ['item_id', 'forecast_date', 'predicted_quantity', 'actual_quantity', 'accuracy_score'];
    protected $casts = ['forecast_date' => 'date', 'accuracy_score' => 'float'];
    
    public function item(): BelongsTo
    public function calculateAccuracy(): float
}
```

**VisitSchedule**
```php
class VisitSchedule extends Model
{
    protected $fillable = ['site_id', 'recommended_date', 'priority', 'reasoning', 'accepted', 'actual_date'];
    protected $casts = ['recommended_date' => 'date', 'actual_date' => 'date', 'accepted' => 'boolean'];
    
    public function site(): BelongsTo
}


## Correctness Properties

A property is a characteristic or behavior that should hold true across all valid executions of a system—essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.

### Property Reflection

After analyzing all 84 acceptance criteria, I identified several opportunities to consolidate redundant properties:

- **Audit logging properties (2.1-2.4)** can be combined into a single comprehensive property about audit trail completeness
- **Backup verification and notification (3.4, 3.5)** are complementary but test different aspects, so both are kept
- **File encryption round-trip (4.1, 4.2)** represents a classic round-trip property
- **Role permission properties (6.4, 6.5)** test different aspects of permission aggregation and should remain separate
- **Validation properties (7.1-7.8)** cover different validation types and should remain separate for clarity
- **AI recommendation properties** are kept separate as they test distinct capabilities

### High Priority Properties (Security & Reliability)

Property 1: MFA Login Enforcement
*For any* user with MFA enabled, attempting to log in with valid credentials should require successful MFA verification before granting access
**Validates: Requirements 1.1**

Property 2: MFA Code Rejection and Logging
*For any* user with MFA enabled, entering an incorrect MFA code should result in login rejection and creation of a failed attempt log entry
**Validates: Requirements 1.4**

Property 3: MFA Session Creation
*For any* user who successfully completes MFA verification, the system should create a trusted session with the configured timeout duration
**Validates: Requirements 1.7**

Property 4: Comprehensive Audit Trail
*For any* critical system operation (password change, stock update, account modification, document access), the system should create an immutable log entry containing all required fields (user ID, timestamp, IP address, old/new values)
**Validates: Requirements 2.1, 2.2, 2.3, 2.4**

Property 5: Audit Log Immutability
*For any* created audit log entry, attempts to modify or delete the entry should be rejected by the system
**Validates: Requirements 2.7**

Property 6: Audit Log Search
*For any* archived audit logs, Super_Admin should be able to search and retrieve historical records matching specified criteria
**Validates: Requirements 2.6**

Property 7: Backup Completeness
*For any* backup created by the system, the backup should include all database tables, uploaded files, and configuration data
**Validates: Requirements 3.3**

Property 8: Backup Verification
*For any* completed backup, the system should verify backup integrity before marking it as complete
**Validates: Requirements 3.4**

Property 9: Backup Failure Notification
*For any* failed backup operation, the system should send an email notification to Super_Admin with error details
**Validates: Requirements 3.5**

Property 10: Backup Retention Policy
*For any* backup set exceeding 6 monthly backups, the system should automatically delete the oldest backups to maintain the retention limit
**Validates: Requirements 3.6**

Property 11: Low Storage Alert
*For any* backup operation when storage space is insufficient, the system should alert Super_Admin before the next scheduled backup
**Validates: Requirements 3.7**

Property 12: File Encryption Round-Trip
*For any* confidential document uploaded to the system, encrypting then decrypting the file should produce content identical to the original
**Validates: Requirements 4.1, 4.2**

Property 13: Encryption Key Separation
*For any* encrypted file, the encryption key should be stored in a location separate from the encrypted file itself
**Validates: Requirements 4.4**

Property 14: Secure File Deletion
*For any* confidential document marked for deletion, both the encrypted file and associated encryption keys should be removed from storage
**Validates: Requirements 4.5**

Property 15: Unauthorized Access Denial and Logging
*For any* user without appropriate permissions attempting to access a confidential document, the system should deny access and create an audit log entry
**Validates: Requirements 4.6**

Property 16: Confidential File Encryption
*For any* file classified as confidential (site visit documents, client documents, or Super_Admin-marked files), the system should encrypt the file upon upload
**Validates: Requirements 4.7**

Property 17: Environment Error Logging
*For any* hosting environment issue detected, the system should log detailed error information for troubleshooting
**Validates: Requirements 5.5**

Property 18: Production Error Messages
*For any* critical module failure in production, the system should provide clear error messages to administrators
**Validates: Requirements 5.7**

### Medium Priority Properties (System Improvements)

Property 19: Dynamic Role Creation
*For any* new role created by Super_Admin, the Role_Manager should allow assignment of granular permissions for each system module
**Validates: Requirements 6.1**

Property 20: Immediate Permission Propagation
*For any* role permission modification, the changes should apply immediately to all users assigned that role
**Validates: Requirements 6.2**

Property 21: Permission Union
*For any* user assigned multiple roles, the system should grant the union of all permissions from all assigned roles
**Validates: Requirements 6.4**

Property 22: Restrictive Permission Conflict Resolution
*For any* permission conflict across multiple roles, the system should apply the most restrictive permission
**Validates: Requirements 6.5**

Property 23: Role Deletion Protection
*For any* role currently assigned to active users, attempts to delete the role should be rejected by the system
**Validates: Requirements 6.7**

Property 24: Role Change Audit Trail
*For any* role permission modification, the system should create an audit trail entry logging the change
**Validates: Requirements 6.8**

Property 25: Required Field Validation
*For any* plant inventory data submission, the system should validate that all required fields are populated with appropriate data types
**Validates: Requirements 7.1**

Property 26: Numeric Range Validation
*For any* numeric value entered, the system should enforce minimum and maximum value constraints
**Validates: Requirements 7.2**

Property 27: Logical Date Validation
*For any* date fields populated, the system should ensure dates are logically consistent (e.g., harvest date after planting date)
**Validates: Requirements 7.3**

Property 28: Duplicate Detection
*For any* record submission that duplicates existing data, the system should alert the user and require confirmation before saving
**Validates: Requirements 7.4**

Property 29: Format Validation
*For any* formatted field (email, phone number), the system should validate against standard patterns and reject invalid formats
**Validates: Requirements 7.5**

Property 30: Import Validation
*For any* data imported from external sources, the system should validate all records before committing to the database
**Validates: Requirements 7.6**

Property 31: Validation Error Messages
*For any* validation failure, the system should provide clear, specific error messages indicating which fields require correction
**Validates: Requirements 7.7**

Property 32: Critical Data Confirmation
*For any* critical data modification, the system should require user confirmation before saving changes
**Validates: Requirements 7.8**

Property 33: Report Completeness
*For any* report generated, the system should include all relevant data from the database without omissions
**Validates: Requirements 8.1**

Property 34: Report Data Freshness
*For any* report requested, the system should generate it with current data reflecting the latest database state
**Validates: Requirements 8.3**

Property 35: PDF Report Quality
*For any* PDF report generated in production, the system should produce properly formatted documents with all data visible
**Validates: Requirements 8.5**

Property 36: Date Format Consistency
*For any* report containing dates, the system should format all dates consistently according to system locale settings
**Validates: Requirements 8.6**

### Low Priority Properties (AI Features)

Property 37: Site Condition Analysis
*For any* plant recommendation request, the AI_Module should analyze provided site conditions (soil type, climate, available space)
**Validates: Requirements 9.1**

Property 38: Preference Consideration
*For any* recommendation generation, the AI_Module should consider user preferences and historical success rates
**Validates: Requirements 9.2**

Property 39: Recommendation Count and Ranking
*For any* recommendation request with sufficient data, the AI_Module should provide at least 3 plant recommendations ranked by suitability score
**Validates: Requirements 9.3**

Property 40: Recommendation Explainability
*For any* plant recommendation provided, the system should include an explanation of the reasoning behind the suggestion
**Validates: Requirements 9.4**

Property 41: Insufficient Data Handling
*For any* recommendation request with insufficient data, the AI_Module should request additional information from the user
**Validates: Requirements 9.5**

Property 42: Recommendation Outcome Tracking
*For any* accepted recommendation, the system should track the outcome for future learning
**Validates: Requirements 9.6**

Property 43: Disease Image Analysis
*For any* plant photo uploaded, the AI_Module should analyze the image for signs of disease or pest damage
**Validates: Requirements 10.1**

Property 44: Disease Identification
*For any* disease detected in an image, the AI_Module should identify the specific disease or pest with a confidence score
**Validates: Requirements 10.2**

Property 45: Treatment Recommendations
*For any* identified disease, the AI_Module should provide treatment recommendations
**Validates: Requirements 10.3**

Property 46: Healthy Plant Confirmation
*For any* plant photo with no disease detected, the AI_Module should confirm the plant appears healthy
**Validates: Requirements 10.4**

Property 47: Image Quality Validation
*For any* uploaded image with insufficient quality for analysis, the AI_Module should request a clearer photo
**Validates: Requirements 10.5**

Property 48: Analysis Result Persistence
*For any* completed disease analysis, the system should store the photo and analysis results for future reference
**Validates: Requirements 10.6**

Property 49: Historical Consumption Analysis
*For any* inventory forecast generated, the system should analyze historical inventory consumption patterns
**Validates: Requirements 11.1**

Property 50: Multi-Timeframe Forecasting
*For any* inventory item, the AI_Module should predict inventory needs for 30, 60, and 90 days
**Validates: Requirements 11.2**

Property 51: Seasonal Consideration
*For any* forecast generated, the AI_Module should consider seasonal variations and historical trends
**Validates: Requirements 11.3**

Property 52: Low Inventory Alerts
*For any* inventory forecast predicting levels below minimum thresholds, the system should alert administrators
**Validates: Requirements 11.4**

Property 53: Forecast Accuracy Display
*For any* inventory item with forecasts, the system should display forecast accuracy metrics
**Validates: Requirements 11.6**

Property 54: Cold-Start Forecasting
*For any* new inventory item added, the AI_Module should use similar items' data for initial forecasting
**Validates: Requirements 11.7**

Property 55: Historical Visit Analysis
*For any* site visit scheduling request, the AI_Module should analyze historical visit patterns and outcomes
**Validates: Requirements 12.1**

Property 56: Optimal Date Recommendations
*For any* site visit to be scheduled, the AI_Module should recommend optimal visit dates based on plant growth stages and care requirements
**Validates: Requirements 12.2**

Property 57: Efficient Route Scheduling
*For any* set of multiple pending visits, the AI_Module should suggest efficient routing and scheduling
**Validates: Requirements 12.3**

Property 58: Conflict Resolution
*For any* resource conflict in scheduling, the AI_Module should propose alternative scheduling options
**Validates: Requirements 12.4**

Property 59: Visit Outcome Tracking
*For any* completed site visit, the system should record outcomes to improve future scheduling recommendations
**Validates: Requirements 12.5**

Property 60: Weather Consideration
*For any* outdoor site visit recommendation, the AI_Module should consider weather forecasts
**Validates: Requirements 12.6**

Property 61: Urgent Visit Prioritization
*For any* urgent site visit required, the system should prioritize it over routine scheduled visits
**Validates: Requirements 12.7**

## Error Handling

### MFA Error Handling

1. **Invalid MFA Code**: Return clear error message, increment failed attempt counter, lock account after 5 failed attempts
2. **MFA Setup Failure**: Rollback MFA configuration, notify user of failure reason
3. **MFA Recovery**: Require Super_Admin approval, send notification to user and admin
4. **Session Timeout**: Clear MFA session, require re-authentication

### Audit Trail Error Handling

1. **Log Write Failure**: Retry up to 3 times, alert Super_Admin if persistent failure
2. **Archive Failure**: Retry next day, alert Super_Admin, maintain logs in primary table
3. **Search Timeout**: Return partial results with timeout warning
4. **Immutability Violation**: Reject operation, log security incident, alert Super_Admin

### Backup Error Handling

1. **Backup Creation Failure**: Log detailed error, notify Super_Admin via email, retry on next schedule
2. **Verification Failure**: Mark backup as unverified, alert Super_Admin, retain for investigation
3. **Storage Full**: Alert Super_Admin immediately, suggest cleanup actions, pause scheduled backups
4. **Restore Failure**: Log error details, provide rollback option, notify Super_Admin

### Encryption Error Handling

1. **Encryption Failure**: Reject file upload, return error to user, log incident
2. **Decryption Failure**: Return error message, log incident, alert Super_Admin if key corruption suspected
3. **Key Generation Failure**: Retry up to 3 times, reject upload if persistent, log error
4. **Key Storage Failure**: Rollback file upload, alert Super_Admin, log critical error

### Hosting Environment Error Handling

1. **Email Send Failure**: Queue for retry, log failure, alert admin after 3 failed attempts
2. **File Upload Failure**: Return clear error to user, log with environment details
3. **PDF Generation Failure**: Offer alternative formats, log error with memory/configuration details
4. **Environment Check Failure**: Display diagnostic report, provide remediation steps

### Role Management Error Handling

1. **Permission Conflict**: Apply most restrictive, log conflict for admin review
2. **Role Deletion Attempt**: Reject with clear message listing affected users
3. **Permission Propagation Failure**: Retry, log error, alert Super_Admin if persistent
4. **Circular Role Dependencies**: Detect and reject, provide clear error message

### Validation Error Handling

1. **Required Field Missing**: Highlight field, provide specific error message
2. **Format Validation Failure**: Show expected format example, highlight invalid field
3. **Range Validation Failure**: Display valid range, highlight invalid value
4. **Duplicate Detection**: Show existing record, offer options (cancel, force save, merge)
5. **Import Validation Failure**: Provide detailed error report with row numbers, allow partial import of valid rows

### Report Generation Error Handling

1. **Data Retrieval Failure**: Log error, retry query, return error message if persistent
2. **PDF Generation Failure**: Offer CSV/Excel alternative, log error with details
3. **Empty Report**: Display clear "no data" message with filter suggestions
4. **Export Timeout**: Offer to email report when ready, process in background

### AI Module Error Handling

1. **Insufficient Training Data**: Return message requesting more data, suggest manual alternatives
2. **Image Analysis Failure**: Request clearer image, provide troubleshooting tips
3. **Forecast Generation Failure**: Fall back to simple average, log error for model improvement
4. **API Timeout**: Retry with exponential backoff, cache previous results, notify user of delay
5. **Model Prediction Error**: Log error, return conservative estimate, flag for review

## Testing Strategy

### Dual Testing Approach

This feature requires both unit testing and property-based testing for comprehensive coverage:

- **Unit tests**: Verify specific examples, edge cases, and error conditions
- **Property tests**: Verify universal properties across all inputs using randomized test data

Both approaches are complementary and necessary. Unit tests catch concrete bugs in specific scenarios, while property tests verify general correctness across a wide range of inputs.

### Property-Based Testing Configuration

**Framework**: Use **PestPHP** with **Pest Property Testing** plugin for Laravel

**Configuration**:
- Minimum 100 iterations per property test (due to randomization)
- Each property test must reference its design document property
- Tag format: `Feature: questionnaire-enhancements, Property {number}: {property_text}`

**Example Property Test Structure**:
```php
test('Property 12: File Encryption Round-Trip', function () {
    // Feature: questionnaire-enhancements, Property 12: File Encryption Round-Trip
    
    forAll(
        Generator\file(), // Random file generator
        Generator\string() // Random filename
    )->then(function ($fileContent, $filename) {
        $originalFile = createTestFile($fileContent, $filename);
        
        // Encrypt the file
        $encryptedFile = app(EncryptionService::class)->encryptFile($originalFile);
        
        // Decrypt the file
        $decryptedContent = app(EncryptionService::class)->decryptFile($encryptedFile->encrypted_path);
        
        // Verify round-trip preserves content
        expect($decryptedContent)->toBe($fileContent);
    })->runs(100);
});
```

### Unit Testing Focus Areas

Unit tests should focus on:

1. **Specific Examples**:
   - MFA setup with email method
   - Audit log creation for password change
   - Backup creation and verification
   - File encryption with AES-256

2. **Edge Cases**:
   - Empty report generation (8.7)
   - MFA recovery with lost device
   - Backup with insufficient storage
   - Image analysis with poor quality photo

3. **Integration Points**:
   - MFA middleware integration with Laravel auth
   - Audit service integration with existing logging
   - Backup scheduler integration with Laravel scheduler
   - Encryption service integration with file storage

4. **Error Conditions**:
   - Failed MFA verification
   - Backup creation failure
   - Encryption key generation failure
   - Invalid role permission assignment

### Property Testing Focus Areas

Property tests should verify:

1. **Invariants**:
   - Audit logs remain immutable after creation (Property 5)
   - Encryption keys stored separately from files (Property 13)
   - Role permissions propagate immediately (Property 20)

2. **Round-Trip Properties**:
   - File encryption/decryption preserves content (Property 12)
   - Backup/restore preserves data integrity

3. **Idempotence**:
   - Multiple identical audit log requests create single entry
   - Repeated role permission assignments have same effect

4. **Metamorphic Properties**:
   - Adding permissions increases or maintains access level
   - Backup retention policy maintains exactly 6 backups

5. **Error Conditions**:
   - Invalid MFA codes always rejected (Property 2)
   - Unauthorized access always denied and logged (Property 15)
   - Invalid data always rejected with clear messages (Property 31)

### Test Coverage Goals

- **High Priority Features**: 90%+ code coverage, all properties tested
- **Medium Priority Features**: 80%+ code coverage, critical properties tested
- **Low Priority Features (AI)**: 70%+ code coverage, core properties tested

### Testing Phases

**Phase 1: High Priority (Security & Reliability)**
- Implement and test MFA, Audit Trail, Backup, Encryption, Hosting fixes
- Focus on security properties and error handling
- Target: 100% of high priority properties tested

**Phase 2: Medium Priority (System Improvements)**
- Implement and test Role Management, Validation, Reporting
- Focus on data integrity and user experience
- Target: 100% of medium priority properties tested

**Phase 3: Low Priority (AI Features)**
- Implement and test AI modules incrementally
- Focus on core AI properties, defer advanced learning properties
- Target: 80% of low priority properties tested (defer adaptive learning properties 9.7, 11.5)

### Continuous Testing

- Run property tests in CI/CD pipeline with 100 iterations
- Run full test suite on every commit to main branch
- Monitor property test failures for potential bugs
- Review and update generators as system evolves
