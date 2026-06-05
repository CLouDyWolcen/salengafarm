# Requirements Document: Audit Trail System

## Introduction

This document outlines the requirements for implementing an Immutable Audit Trail system for the Salenga Farm Inventory System. The audit trail will provide accountability, security monitoring, and compliance by tracking all critical user actions in a tamper-proof database.

**Timeline:** 2 days  
**Priority:** 🔴 CRITICAL  
**User Access:** Super Admin only

## Glossary

- **Audit_Trail**: Immutable chronological record of all critical system activities
- **Audit_Log**: A single recorded event in the audit trail (database row)
- **Super_Admin**: User with highest system access (role = 'super_admin')
- **Admin**: User with administrative access (role = 'admin')
- **Client**: System user with client access (role = 'client')
- **Critical_Operation**: Any action that modifies data or accesses sensitive information
- **Immutable**: Cannot be modified or deleted after creation

## Current System Context

### Existing Logging
- ✅ **System Logs** - Technical error logs in `storage/logs/laravel.log`
- ✅ Accessible via dashboard modal (Super Admin only)
- ❌ Plain text file (can be edited, not secure)
- ❌ Only logs technical errors, not user actions

### New Audit Trail (What We're Building)
- ✅ **Database-based** - Stored in `audit_logs` table
- ✅ **Immutable** - Cannot be edited or deleted (database triggers)
- ✅ **User Actions** - Tracks who did what, when
- ✅ **Super Admin Only** - Dashboard access restricted

## Requirements

### Requirement 1: Audit Trail Database Storage

**User Story:** As a Super Admin, I want all critical user actions stored in a secure database table, so that I have a permanent, tamper-proof record of system activity.

#### Acceptance Criteria

1. **THE** system **SHALL** create an `audit_logs` database table with the following columns:
   - `id` (auto-increment primary key)
   - `user_id` (foreign key to users table, nullable)
   - `user_email` (stores email even if user is deleted)
   - `user_role` (role at time of action: super_admin/admin/client)
   - `action` (e.g., "User Login", "Stock Updated", "Sale Created")
   - `entity_type` (e.g., "User", "Plant", "Sale", "Request")
   - `entity_id` (ID of the affected record)
   - `old_values` (JSON - data before change)
   - `new_values` (JSON - data after change)
   - `ip_address` (user's IP address)
   - `user_agent` (browser/device information)
   - `url` (request URL)
   - `created_at` (timestamp, not updatable)

2. **THE** system **SHALL** implement database triggers to prevent:
   - Updating any audit log record (UPDATE blocked)
   - Deleting any audit log record (DELETE blocked)

3. **THE** `audit_logs` table **SHALL** have indexes on:
   - `user_id` (for fast user activity queries)
   - `entity_type` + `entity_id` (for fast entity history queries)
   - `created_at` (for fast date range queries)
   - `action` (for fast action type filtering)

---

### Requirement 2: Automatic Audit Logging

**User Story:** As a Super Admin, I want the system to automatically log all critical operations without manual intervention, so that no important actions are missed.

#### Acceptance Criteria

1. **WHEN** any of the following actions occur, **THE** system **SHALL** create an audit log entry:

   **User Authentication:**
   - User login (successful and failed attempts)
   - User logout
   - Password change
   - Password reset request

   **User Management:**
   - User account creation
   - User role change
   - User deletion
   - Profile updates

   **Inventory Operations:**
   - Plant creation
   - Plant update (especially stock changes)
   - Plant deletion
   - Bulk plant updates

   **Sales Operations:**
   - Walk-in sale creation
   - Sale record modification
   - Sale deletion

   **Request Operations:**
   - Client RFQ submission
   - Request status change
   - Response sent to client
   - Request deletion

   **Site Visit Operations:**
   - Site visit creation
   - Site visit modification
   - Document upload/deletion
   - Site visit deletion

2. **THE** system **SHALL** use an `AuditService` class for centralized logging logic

3. **THE** audit log entry **SHALL** capture:
   - Who performed the action (user_id, email, role)
   - What action was performed (descriptive text)
   - When it occurred (timestamp)
   - Where it came from (IP address, URL)
   - What changed (old values vs new values in JSON)

4. **WHERE** a user is not logged in (e.g., failed login attempt), **THE** system **SHALL**:
   - Set `user_id` to NULL
   - Store attempted email in `user_email` field
   - Still log the IP address and action

---

### Requirement 3: Audit Trail Dashboard Interface

**User Story:** As a Super Admin, I want a user-friendly dashboard to view, search, and filter audit logs, so that I can quickly investigate user activity and security incidents.

#### Acceptance Criteria

1. **WHEN** a Super Admin clicks "Audit Trail" button in Quick Actions, **THE** system **SHALL**:
   - Open the audit trail page at `/admin/audit-logs`
   - Display paginated list of audit log entries (newest first)
   - Show 50 entries per page

2. **THE** audit trail interface **SHALL** display for each log entry:
   - Timestamp (formatted as "Jun 6, 2026 10:30 AM")
   - User email and role badge
   - Action description (e.g., "Updated Plant Stock")
   - Entity type and ID (e.g., "Plant #42")
   - IP address
   - "View Details" button

3. **THE** audit trail interface **SHALL** provide filtering by:
   - **Date Range**: Today / Last 7 days / Last 30 days / Custom range
   - **User**: Dropdown of all users
   - **Action Type**: Dropdown (Login, Update, Delete, Create, etc.)
   - **Entity Type**: Dropdown (User, Plant, Sale, Request, Site Visit)

4. **THE** audit trail interface **SHALL** provide search by:
   - User email
   - Action description
   - IP address

5. **WHEN** a Super Admin clicks "View Details" on a log entry, **THE** system **SHALL**:
   - Show a modal with full log details
   - Display old values vs new values in side-by-side comparison
   - Format JSON data in readable structure
   - Show complete user agent string
   - Display full URL

6. **THE** audit trail interface **SHALL** include:
   - Export to CSV button (exports filtered results)
   - Refresh button (reload logs)
   - Summary statistics at top:
     - Total logs today
     - Unique users today
     - Failed login attempts today
     - Critical actions today (deletions, role changes)

7. **THE** audit trail interface **SHALL** use color coding:
   - 🔴 Red for critical actions (deletions, role changes)
   - 🟡 Yellow for failed login attempts
   - 🟢 Green for successful operations
   - 🔵 Blue for read-only actions

---

### Requirement 4: Dashboard Integration

**User Story:** As a Super Admin, I want quick access to the audit trail from the dashboard, so that I can easily monitor system activity.

#### Acceptance Criteria

1. **THE** system **SHALL** add an "Audit Trail" button in the Quick Actions section

2. **THE** "Audit Trail" button **SHALL**:
   - Be placed directly below the "System Logs" button
   - Only be visible to Super Admin role
   - Use icon: `<i class="fas fa-clipboard-list"></i>`
   - Use color: `btn btn-warning` (orange/yellow to differentiate from System Logs)

3. **THE** button layout **SHALL** be:
   ```
   Quick Actions (Super Admin)
   ├── Update Stock (blue)
   ├── System Logs (info blue)
   ├── Audit Trail (warning yellow) ← NEW
   └── Sales Records (green)
   ```

4. **WHEN** clicked, **THE** button **SHALL**:
   - Navigate to `/admin/audit-logs` page (not a modal)
   - Load with default filter: Last 7 days

---

### Requirement 5: Entity History Feature

**User Story:** As a Super Admin, I want to see the complete history of changes for any specific record, so that I can track how data evolved over time.

#### Acceptance Criteria

1. **WHEN** viewing a plant, sale, request, or site visit, **THE** system **SHALL** display a "View History" link (Super Admin only)

2. **WHEN** "View History" is clicked, **THE** system **SHALL**:
   - Open a modal showing all audit logs for that specific entity
   - Display logs in chronological order (oldest to newest)
   - Show who made each change and when
   - Highlight what values changed

3. **THE** history modal **SHALL** display:
   - Timeline visualization
   - User avatar/initials
   - Change description
   - Before/after values

---

### Requirement 6: Failed Login Tracking

**User Story:** As a Super Admin, I want to track failed login attempts, so that I can detect potential security threats.

#### Acceptance Criteria

1. **WHEN** a user attempts to log in with incorrect credentials, **THE** system **SHALL**:
   - Log the failed attempt in audit trail
   - Capture attempted email address
   - Record IP address
   - Store "Login Failed" action

2. **THE** audit trail dashboard **SHALL** display:
   - Count of failed login attempts today
   - List of recent failed attempts with IP addresses
   - Highlight repeated failures from same IP (potential attack)

3. **WHEN** there are 5+ failed login attempts from the same IP within 1 hour, **THE** system **SHALL**:
   - Flag it as "Suspicious Activity" in the audit trail
   - Highlight in red

---

## Technical Implementation Details

### Database Migration

```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('user_email');
    $table->string('user_role');
    $table->string('action');
    $table->string('entity_type')->nullable();
    $table->unsignedBigInteger('entity_id')->nullable();
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45);
    $table->text('user_agent')->nullable();
    $table->string('url')->nullable();
    $table->timestamp('created_at')->useCurrent();
    
    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    $table->index(['user_id', 'created_at']);
    $table->index(['entity_type', 'entity_id']);
    $table->index('action');
    $table->index('created_at');
});

// Prevent updates and deletes
DB::unprepared('
    CREATE TRIGGER prevent_audit_log_update
    BEFORE UPDATE ON audit_logs
    FOR EACH ROW
    BEGIN
        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Audit logs cannot be modified";
    END
');

DB::unprepared('
    CREATE TRIGGER prevent_audit_log_delete
    BEFORE DELETE ON audit_logs
    FOR EACH ROW
    BEGIN
        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Audit logs cannot be deleted";
    END
');
```

### AuditService Class

```php
class AuditService
{
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email ?? request()->input('email', 'Unknown'),
            'user_role' => Auth::user()?->role ?? 'guest',
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
        ]);
    }
}
```

### Usage Examples

```php
// Log user login
AuditService::log('User Login');

// Log stock update
AuditService::log(
    'Stock Updated',
    'Plant',
    $plant->id,
    ['stock_quantity' => 50],
    ['stock_quantity' => 45]
);

// Log sale deletion
AuditService::log(
    'Sale Deleted',
    'Sale',
    $sale->id,
    $sale->toArray(),
    null
);
```

### Routes

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Audit Trail (Super Admin only)
    Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])
        ->name('admin.audit-logs.index');
    Route::get('/admin/audit-logs/export', [AuditLogController::class, 'export'])
        ->name('admin.audit-logs.export');
    Route::get('/admin/audit-logs/{id}', [AuditLogController::class, 'show'])
        ->name('admin.audit-logs.show');
});
```

---

## Non-Functional Requirements

### Performance
- Audit logging should add less than 50ms overhead per request
- Audit trail page should load within 2 seconds
- Support up to 100,000 audit log records without performance degradation

### Security
- Only Super Admin can view audit trail
- Database triggers enforce immutability
- Sensitive data (passwords) never logged

### Reliability
- If audit logging fails, the main operation should still succeed
- Use database transactions where appropriate

### Usability
- Clear, human-readable action descriptions
- Intuitive filtering and search
- Responsive design for mobile viewing

---

## Success Criteria

Implementation is successful when:

1. ✅ All critical operations are automatically logged
2. ✅ Audit logs cannot be modified or deleted (tested)
3. ✅ Super Admin can view and search audit trail
4. ✅ Failed login attempts are tracked
5. ✅ Export to CSV works correctly
6. ✅ Performance overhead is acceptable (<50ms)
7. ✅ "Audit Trail" button appears in dashboard Quick Actions
8. ✅ Entity history can be viewed for any record

---

## Implementation Timeline (2 Days)

### Day 1: Core Infrastructure (6 hours)
- [ ] Create `audit_logs` migration with triggers (1 hour)
- [ ] Create `AuditLog` model (30 min)
- [ ] Create `AuditService` class (1 hour)
- [ ] Add audit logging to authentication (login/logout) (1 hour)
- [ ] Add audit logging to user management (1 hour)
- [ ] Add audit logging to inventory operations (1.5 hours)

### Day 2: Dashboard & Polish (6 hours)
- [ ] Add audit logging to sales & requests (1 hour)
- [ ] Create `AuditLogController` (1 hour)
- [ ] Create audit trail view page (2 hours)
- [ ] Add "Audit Trail" button to dashboard (30 min)
- [ ] Test filtering and search (1 hour)
- [ ] Test immutability (try to edit/delete logs) (30 min)

---

## Testing Checklist

- [ ] Create test user and log in (check audit log created)
- [ ] Update plant stock (check old/new values logged)
- [ ] Delete a sale (check deletion logged)
- [ ] Attempt failed login (check failed attempt logged)
- [ ] Try to edit audit log via SQL (should fail)
- [ ] Try to delete audit log via SQL (should fail)
- [ ] Filter by date range (verify results)
- [ ] Search by email (verify results)
- [ ] Export to CSV (verify file downloads)
- [ ] View log details modal (verify JSON display)
- [ ] Check performance with 1000+ logs
- [ ] Verify only Super Admin can access

---

## Out of Scope (Future Enhancements)

- Real-time audit log notifications
- Automated monthly archiving to cloud storage
- Advanced analytics and reporting
- Blockchain-based tamper verification
- Integration with external SIEM systems

---

**Document Version:** 1.0  
**Created:** June 6, 2026  
**Author:** Development Team  
**Status:** Ready for Implementation
