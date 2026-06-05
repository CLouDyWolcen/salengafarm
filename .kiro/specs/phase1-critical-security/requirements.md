# Requirements Document: Phase 1 - Critical Security Enhancements

## Introduction

This document captures the requirements for Phase 1 of the DigitalOcean Panel Recommendations implementation for the Comprehensive Plant Inventory and Site Visit Management System for Salenga Farm. Phase 1 focuses on critical security and data protection features that must be implemented immediately to ensure production system reliability, data safety, and regulatory compliance.

**Timeline:** 2 days  
**Priority:** 🔴 CRITICAL  
**Dependencies:** DigitalOcean production environment, MySQL database, existing Laravel application

## Glossary

- **System**: The Comprehensive Plant Inventory and Site Visit Management System deployed on DigitalOcean
- **Backup_System**: Automated data backup and recovery mechanism with cloud storage
- **Audit_Trail**: Immutable chronological record of all critical system activities and changes
- **Log_Entry**: An immutable recorded event or change in the system stored in the database
- **Super_Admin**: User with highest level of system access and control (role = 'super_admin')
- **Admin**: User with administrative access to inventory, sales, and requests (role = 'admin')
- **Client**: System user with client access (role = 'client')
- **Critical_Operation**: Any system action that modifies data, changes permissions, or accesses sensitive information
- **Cloud_Storage**: External storage service for offsite backup retention (DigitalOcean Spaces, AWS S3, or equivalent)
- **Production_Server**: DigitalOcean Ubuntu 24.04 droplet at IP 165.245.182.243
- **Database**: MySQL 8.0 database (salengafarm_db on production, inventory locally)
- **Confidential_Data**: User passwords, client documents, site visit files, business data

## Current System Context

### Existing Backup Implementation
- ✅ Manual backup script (`backup_database.bat`) for local Windows environment
- ✅ Backs up MySQL database only
- ✅ Keeps last 7 backups locally
- ❌ NOT automated (requires manual execution)
- ❌ NOT cloud-based (local storage only)
- ❌ Does NOT backup files/uploads
- ❌ No offsite redundancy

### Existing Audit/Logging Implementation
- ✅ `SystemLogController` reads Laravel logs from `storage/logs/laravel.log`
- ✅ Super Admin can view/download/clear logs via dashboard
- ✅ Backup created before clearing logs
- ❌ Logs are NOT immutable (plain text file can be edited)
- ❌ No database-based audit trail
- ❌ No tracking of specific critical operations
- ❌ No per-user activity chronology
- ❌ No retention policy

## Requirements

### Requirement 1: Automated Cloud Backup System

**Priority:** 🔴 CRITICAL  
**User Story:** As a system administrator, I want automated cloud backups with daily scheduling and offsite storage, so that the system can recover from any data loss scenario including server failure, ransomware, or accidental deletion.

#### Acceptance Criteria

1. **WHEN** the backup schedule triggers (daily at 2:00 AM server time), **THE** Backup_System **SHALL** automatically create a complete system backup without manual intervention

2. **WHEN** a backup executes, **THE** Backup_System **SHALL** include:
   - Complete MySQL database dump (all tables and data)
   - All uploaded files from `storage/app/`
   - All public files from `public/storage/` and `public/plant-photos/`
   - Site visit media files
   - Application `.env` file (for configuration recovery)

3. **WHEN** a backup is created, **THE** Backup_System **SHALL**:
   - Compress the backup into a single `.tar.gz` archive
   - Name it with timestamp format: `salengafarm_backup_YYYYMMDD_HHMMSS.tar.gz`
   - Calculate and store MD5 checksum for integrity verification

4. **WHEN** a backup completes compression, **THE** Backup_System **SHALL**:
   - Upload the backup to Cloud_Storage (DigitalOcean Spaces recommended)
   - Verify successful upload by checking file size and checksum
   - Keep a local copy on Production_Server for quick recovery (last 3 backups)

5. **WHEN** a backup completes successfully, **THE** Backup_System **SHALL**:
   - Log success to system logs with timestamp, file size, and upload status
   - Send email notification to Super_Admin with backup summary
   - Record backup metadata in `backup_logs` database table

6. **WHEN** a backup fails at any step, **THE** Backup_System **SHALL**:
   - Log detailed error information including failure point and error message
   - Send immediate email alert to Super_Admin with error details
   - Retry the backup automatically after 1 hour (maximum 3 retry attempts)

7. **THE** Backup_System **SHALL** implement retention policy:
   - Keep daily backups for last 30 days
   - Keep weekly backups (every Sunday) for last 3 months
   - Keep monthly backups (1st of month) for last 12 months
   - Automatically delete backups older than retention period from both local and cloud storage

8. **WHERE** Cloud_Storage space is above 80% capacity, **THE** Backup_System **SHALL**:
   - Send warning email to Super_Admin
   - Continue normal backup operation
   - Display warning in admin dashboard

9. **WHEN** a Super_Admin requests manual backup via dashboard, **THE** Backup_System **SHALL**:
   - Create immediate backup outside regular schedule
   - Tag it as "manual" in backup metadata
   - Follow same upload and verification process

10. **THE** Backup_System **SHALL** provide backup management interface in admin dashboard:
    - List all available backups (local and cloud) with size, date, and type
    - Allow downloading specific backup files
    - Show backup history and success/failure status
    - Display current storage usage
    - Allow triggering manual backups

11. **THE** Backup_System **SHALL** include restoration procedure documentation:
    - Step-by-step instructions for database restoration
    - File restoration process
    - Full system recovery from backup
    - Testing restoration process quarterly

#### Technical Requirements

- Bash script: `/usr/local/bin/backup-salengafarm.sh`
- Cron job: Daily at 2:00 AM (configurable)
- Cloud storage: DigitalOcean Spaces (S3-compatible) or AWS S3
- Local backup directory: `/var/backups/salengafarm/`
- Database table: `backup_logs` (track backup history)
- Email notifications: via existing Brevo SMTP
- Storage requirement: Estimate 500MB per backup, ~15GB per month

---

### Requirement 2: Immutable Audit Trail System

**Priority:** 🔴 CRITICAL  
**User Story:** As a Super Admin, I want an immutable database-based audit trail that logs all critical system operations, so that I can ensure accountability, investigate security incidents, and maintain compliance with data protection requirements.

#### Acceptance Criteria

1. **WHEN** any Critical_Operation occurs, **THE** System **SHALL** create an immutable Log_Entry in the `audit_logs` database table containing:
   - Unique log ID (auto-increment)
   - Timestamp (with timezone)
   - User ID (who performed the action)
   - User role at time of action
   - IP address of requester
   - Action type (category of operation)
   - Entity type (what was affected: User, Plant, Request, etc.)
   - Entity ID (specific record affected)
   - Action description (human-readable summary)
   - Old values (JSON - before the change)
   - New values (JSON - after the change)
   - Request URL
   - User agent
   - Session ID

2. **THE** System **SHALL** log the following Critical_Operations:

   **User Account Operations:**
   - User registration
   - User login (successful and failed attempts)
   - User logout
   - Password change
   - Password reset request
   - Email change
   - Role change (user promotion/demotion)
   - User deletion
   - Account suspension/activation
   - Profile updates (especially client information)

   **Inventory Operations:**
   - Plant creation
   - Plant modification (track stock changes, price changes)
   - Plant deletion
   - Bulk plant updates
   - Stock adjustments

   **Sales Operations:**
   - Walk-in sale transaction
   - Sale record deletion
   - Sale record modification

   **Request Operations:**
   - Client RFQ submission
   - Client inquiry submission
   - Request status change
   - Request modification by admin
   - Response sent to client
   - Request deletion

   **Site Visit Operations:**
   - Site visit creation
   - Site visit modification
   - Document upload/download/deletion
   - Client data upload
   - Site visit status change
   - Site visit deletion

   **Administrative Operations:**
   - System log access
   - System log clearing
   - Backup creation (manual)
   - System settings change
   - Notification creation/deletion

3. **WHEN** a Log_Entry is created, **THE** System **SHALL**:
   - Insert the record into the database with a database-level timestamp
   - Use database constraints to prevent modification or deletion of log entries
   - Hash critical log data to detect tampering attempts

4. **WHERE** a user attempts to directly modify or delete records from `audit_logs` table, **THE** Database **SHALL**:
   - Reject the operation (database triggers prevent DELETE and UPDATE)
   - Log the tampering attempt as a new audit entry

5. **WHEN** the end of each month occurs, **THE** System **SHALL**:
   - Automatically export that month's audit logs to compressed archive
   - Store archive in Cloud_Storage (same location as backups)
   - Create monthly summary report (total operations by type, user activity)
   - Send summary report to Super_Admin via email

6. **WHEN** a Super_Admin accesses the audit trail interface, **THE** System **SHALL** provide:
   - Searchable list of all audit entries
   - Filtering by:
     - Date range (last 24 hours, 7 days, 30 days, custom)
     - User (dropdown of all users)
     - Action type (category filter)
     - Entity type (User, Plant, Request, etc.)
   - Sorting by timestamp (newest/oldest first)
   - Export filtered results to CSV
   - Pagination (100 entries per page)
   - Detailed view of each log entry with full JSON data

7. **THE** System **SHALL** retain audit logs:
   - Active logs: Last 12 months in database (queryable via interface)
   - Archived logs: 12+ months in Cloud_Storage (downloadable)
   - Minimum retention: 24 months total

8. **WHERE** audit log database table size exceeds 1GB, **THE** System **SHALL**:
   - Alert Super_Admin via email
   - Continue normal logging operation
   - Recommend archiving older logs

9. **WHEN** displaying audit logs, **THE** System **SHALL**:
   - Show user-friendly descriptions (e.g., "John Doe updated Plant #42 stock from 50 to 45")
   - Highlight sensitive operations (password changes, role changes, deletions) with warning color
   - Display time in local timezone
   - Mask sensitive data (passwords, tokens) in log display

10. **THE** System **SHALL** provide audit trail reports:
    - User activity report (all actions by specific user)
    - Entity history report (all changes to specific record)
    - Security incident report (failed logins, suspicious activity)
    - Compliance report (all data access and modifications)

11. **WHEN** a Super_Admin views audit logs, **THE** System **SHALL**:
    - Create an audit entry for the audit log access itself (meta-logging)
    - Track which logs were viewed and by whom

#### Technical Requirements

- Database table: `audit_logs` (with triggers to prevent modification/deletion)
- Middleware: `AuditMiddleware` (automatically log all write operations)
- Service: `AuditService` (centralized logging logic)
- View: `/admin/audit-logs` (Super Admin only)
- Model: `AuditLog` (read-only model)
- Monthly archive: Cron job on 1st of month at 3:00 AM
- Storage: Indexed database queries for performance
- Hash algorithm: SHA-256 for tamper detection

---

### Requirement 3: Backup Management Dashboard Interface

**Priority:** 🟡 MEDIUM  
**User Story:** As a Super Admin, I want a dashboard interface to monitor backup status, view backup history, and manage backup operations, so that I can ensure data protection is working correctly without accessing the server directly.

#### Acceptance Criteria

1. **WHEN** a Super_Admin navigates to `/admin/backups`, **THE** System **SHALL** display:
   - Current backup status (last backup time, success/failure)
   - Next scheduled backup time
   - List of recent backups (last 30 days) with:
     - Backup date/time
     - Backup type (automatic/manual)
     - File size
     - Location (local/cloud/both)
     - Status (complete/failed/in progress)
     - Download button (for completed backups)
   - Storage usage statistics (local and cloud)
   - Backup health indicators (success rate, storage availability)

2. **WHEN** a Super_Admin clicks "Create Backup Now", **THE** System **SHALL**:
   - Trigger immediate backup process
   - Show progress indicator
   - Provide real-time status updates
   - Display completion message with download link

3. **WHEN** a backup is in progress, **THE** System **SHALL**:
   - Show progress bar or spinner
   - Display current step (e.g., "Exporting database...", "Compressing files...", "Uploading to cloud...")
   - Prevent starting another backup until current one completes

4. **WHEN** a Super_Admin downloads a backup, **THE** System **SHALL**:
   - Log the download action in audit trail
   - Verify user has Super_Admin permissions
   - Stream the backup file from local storage or cloud storage

5. **THE** backup management interface **SHALL** include:
   - Quick restore guide/documentation link
   - Test backup button (verifies backup integrity)
   - Configuration settings (backup schedule, retention policy, cloud credentials)
   - Email notification preferences

---

### Requirement 4: Audit Trail Dashboard Interface

**Priority:** 🟡 MEDIUM  
**User Story:** As a Super Admin, I want a user-friendly audit trail interface with search, filtering, and reporting capabilities, so that I can quickly investigate security incidents and monitor system usage.

#### Acceptance Criteria

1. **WHEN** a Super_Admin navigates to `/admin/audit-logs`, **THE** System **SHALL** display:
   - Summary statistics:
     - Total operations today
     - Total unique users today
     - Failed login attempts today
     - Critical operations today (deletions, role changes)
   - Recent activity feed (last 50 entries)
   - Search and filter controls
   - Export button

2. **WHEN** a Super_Admin uses the search function, **THE** System **SHALL**:
   - Support searching by:
     - User name/email
     - Action description
     - Entity type
     - Date/time range
   - Highlight matching text in results
   - Show result count

3. **WHEN** a Super_Admin applies filters, **THE** System **SHALL**:
   - Allow multiple simultaneous filters
   - Show active filters as badges (removable)
   - Update results in real-time
   - Preserve filter state in URL for sharing

4. **WHEN** viewing an individual audit log entry, **THE** System **SHALL** display:
   - All metadata in organized sections
   - JSON diff viewer for old/new values (color-coded changes)
   - Related log entries (other actions on same entity)
   - Timeline visualization

5. **THE** audit trail interface **SHALL** include visualization:
   - Activity heatmap (operations over time)
   - User activity chart (top active users)
   - Operation type breakdown (pie chart)
   - Failed login attempts timeline

---

## Non-Functional Requirements

### Performance
- Backup creation shall not impact system performance (run during low-traffic hours)
- Audit logging shall add less than 50ms overhead per request
- Audit log queries shall return results within 2 seconds for date ranges up to 30 days

### Security
- Backup files shall be encrypted during transmission to cloud storage (TLS/HTTPS)
- Cloud storage credentials shall be stored securely in `.env` file
- Audit logs shall be protected from modification at database level
- Only Super Admin role can access backup and audit trail interfaces

### Scalability
- Audit log table shall support at least 1 million records without performance degradation
- Backup system shall handle database sizes up to 10GB
- Cloud storage shall support at least 100GB of backup data

### Reliability
- Backup system shall have 99.5% success rate
- Failed backups shall retry automatically
- System shall continue operating normally even if backup or audit logging temporarily fails

### Maintainability
- Backup scripts shall be well-documented with inline comments
- Audit logging shall use consistent coding patterns
- Configuration shall be externalized (no hardcoded values)
- Code shall follow Laravel best practices

## Success Criteria

Phase 1 implementation is considered successful when:

1. ✅ Automated daily backups run successfully for 7 consecutive days
2. ✅ Backup files are verified to be restorable (test restoration performed)
3. ✅ Backups are successfully uploaded to cloud storage
4. ✅ Audit trail logs all critical operations without errors
5. ✅ Audit logs are immutable (tampering attempts blocked)
6. ✅ Super Admin can view, search, and export audit logs
7. ✅ Super Admin can monitor backup status and create manual backups
8. ✅ Email notifications work for backup failures
9. ✅ Monthly archiving executes successfully
10. ✅ Documentation is complete for backup restoration and audit trail usage

## Implementation Priority

### Day 1: Automated Cloud Backups
1. Create backup script for production server
2. Set up DigitalOcean Spaces (or AWS S3)
3. Configure cron job
4. Implement email notifications
5. Create `backup_logs` table
6. Test backup and restoration

### Day 2: Immutable Audit Trail
1. Create `audit_logs` table with triggers
2. Implement `AuditService` class
3. Create `AuditMiddleware`
4. Add audit logging to critical operations
5. Create audit trail dashboard interface
6. Test audit logging and immutability

## Dependencies

- DigitalOcean account with Spaces enabled (or AWS S3 account)
- Production server SSH access
- MySQL database access
- Existing Brevo email service (for notifications)
- Laravel 11 framework
- PHP 8.3+

## Risks and Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Backup storage costs exceed budget | Medium | Low | Monitor usage, implement aggressive retention policy |
| Backup process impacts production performance | High | Medium | Schedule during off-peak hours, optimize backup script |
| Audit log table grows too large | Medium | Medium | Implement monthly archiving, database indexing |
| Cloud storage credentials compromised | High | Low | Secure .env file, use IAM roles with minimal permissions |
| Backup restoration fails when needed | Critical | Low | Test restoration quarterly, document process thoroughly |

## Testing Requirements

### Backup System Testing
- [ ] Verify backup script runs without errors
- [ ] Confirm all database tables are included in backup
- [ ] Verify all file directories are backed up
- [ ] Test upload to cloud storage
- [ ] Test download from cloud storage
- [ ] Perform full system restoration from backup
- [ ] Test email notifications for success and failure
- [ ] Verify retention policy deletes old backups
- [ ] Test manual backup trigger
- [ ] Verify backup under high database load

### Audit Trail Testing
- [ ] Verify audit logs created for all critical operations
- [ ] Confirm audit logs are immutable (attempt to modify/delete)
- [ ] Test audit log search functionality
- [ ] Test audit log filtering
- [ ] Verify monthly archiving
- [ ] Test audit trail dashboard interface
- [ ] Confirm performance with 10,000+ log entries
- [ ] Verify JSON diff viewer works correctly
- [ ] Test CSV export functionality
- [ ] Verify tamper detection (hash validation)

## Documentation Deliverables

1. **Backup System Documentation**
   - Backup script documentation (inline comments)
   - Restoration procedure guide
   - Cloud storage setup guide
   - Troubleshooting guide
   - Configuration reference

2. **Audit Trail Documentation**
   - Audit trail usage guide for Super Admins
   - Developer guide for adding audit logging to new features
   - Database schema documentation
   - Query optimization guide
   - Compliance reporting templates

## Future Enhancements (Out of Scope for Phase 1)

- Real-time backup monitoring dashboard with live status
- Point-in-time database recovery
- Automated backup testing (verify restoration periodically)
- Backup encryption at rest (in addition to in-transit)
- Multi-region backup redundancy
- Blockchain-based audit trail verification
- Machine learning for anomaly detection in audit logs
- API for third-party audit trail integrations

---

**Document Version:** 1.0  
**Last Updated:** June 6, 2026  
**Author:** Development Team  
**Approved By:** [Pending]  
**Status:** Ready for Design Phase
