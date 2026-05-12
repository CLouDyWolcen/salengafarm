# Site Visit Request Feature Implementation

## Overview
Implemented a complete Site Visit Request system that allows clients to request site visits and admins to review and approve/reject these requests.

## Implementation Details

### 1. Database
**Migration:** `database/migrations/2026_05_07_000000_create_site_visit_requests_table.php`
- Created `site_visit_requests` table with fields:
  - `user_id` (foreign key to users)
  - `preferred_date` and `preferred_time`
  - `property_address`, `property_size`, `current_condition`
  - `project_description`, `special_requirements`
  - `photos` (JSON array)
  - `status` (pending/approved/rejected)
  - `admin_notes`, `rejection_reason`
  - `reviewed_by` (foreign key to users), `reviewed_at`

### 2. Model
**File:** `app/Models/SiteVisitRequest.php`
- Created SiteVisitRequest model with:
  - Relationships: `user()`, `reviewer()`
  - Accessor: `getStatusBadgeColorAttribute()`
  - Scopes: `byStatus()`, `pending()`, `recent()`
  - Proper casting for dates and JSON fields

### 3. Controller Methods
**File:** `app/Http/Controllers/SiteVisitController.php`
Added the following methods:
- `requestsIndex()` - Display all requests for admins (with tabs for pending/approved/rejected)
- `createRequest()` - Show request form for clients
- `storeRequest()` - Store new request from client
- `approveRequest()` - Approve a request (admin only)
- `rejectRequest()` - Reject a request with reason (admin only)

### 4. Routes
**File:** `routes/web.php`

**Admin Routes (inside admin middleware):**
```php
Route::get('/site-visit-requests', [SiteVisitController::class, 'requestsIndex'])
    ->name('site-visit-requests.index');
Route::patch('/site-visit-requests/{siteVisitRequest}/approve', [SiteVisitController::class, 'approveRequest'])
    ->name('site-visit-requests.approve');
Route::patch('/site-visit-requests/{siteVisitRequest}/reject', [SiteVisitController::class, 'rejectRequest'])
    ->name('site-visit-requests.reject');
```

**Client Routes (inside auth middleware):**
```php
Route::get('/site-visit-requests/create', [SiteVisitController::class, 'createRequest'])
    ->name('site-visit-requests.create');
Route::post('/site-visit-requests', [SiteVisitController::class, 'storeRequest'])
    ->name('site-visit-requests.store');
```

### 5. Views

#### Client Side
**File:** `resources/views/site-visit-requests/create.blade.php`
- Complete request form with:
  - Visit details (preferred date/time)
  - Property information (address, size, condition)
  - Project description and special requirements
  - Photo upload (up to 5 photos, 5MB each)
  - Photo preview with remove functionality
  - Form validation and error display

**File:** `resources/views/client-data/index.blade.php` (updated)
- Added "Request Site Visit" button in top right corner
- Green button with calendar icon

#### Admin Side
**File:** `resources/views/site-visit-requests/index.blade.php`
- Tabbed interface for Pending/Approved/Rejected requests
- Badge counters showing number of requests in each status
- Full request details display
- Approve/Reject action buttons for pending requests
- Modal dialogs for approval (with optional notes) and rejection (with required reason)
- Photo gallery with lightbox view

**File:** `resources/views/site-visit-requests/partials/request-card.blade.php`
- Reusable card component for displaying request details
- Shows all request information including:
  - Client details (name, email, phone)
  - Preferred date/time
  - Property information
  - Project description
  - Special requirements
  - Property photos (clickable thumbnails)
  - Review status and notes (for approved/rejected)
  - Action buttons (for pending)

**File:** `resources/views/site-visits/index.blade.php` (updated)
- Added "Site Visit Requests" button to the left of "Add New Site Visit"
- Info-colored button with inbox icon
- Badge showing pending request count (if any)

### 6. Features

#### Client Features
1. **Request Form:**
   - Easy-to-use form with clear sections
   - Date picker with minimum date validation (today or later)
   - Property condition dropdown
   - Rich text areas for descriptions
   - Photo upload with preview and remove functionality
   - Client-side validation

2. **Access Control:**
   - Only clients with `site_data` page access can request
   - Automatic user_id assignment
   - Redirects to Site Data page after submission

#### Admin Features
1. **Request Management:**
   - Tabbed interface for easy filtering
   - Real-time pending count badge
   - Comprehensive request details view
   - Photo gallery with lightbox

2. **Approval Workflow:**
   - Approve with optional admin notes
   - Reject with required reason
   - Automatic timestamp and reviewer tracking
   - Success messages after actions

3. **Integration:**
   - Seamless integration with existing Site Visits page
   - Clear separation between requests and actual site visits
   - Admin manually creates site visit after approval

### 7. Workflow

**Client Workflow:**
1. Client navigates to Site Data page
2. Clicks "Request Site Visit" button (top right)
3. Fills out request form with property details
4. Optionally uploads photos
5. Submits request
6. Receives confirmation message

**Admin Workflow:**
1. Admin sees pending count badge on Site Visits page
2. Clicks "Site Visit Requests" button
3. Reviews request details in Pending tab
4. Either:
   - **Approve:** Adds optional notes, approves request
   - **Reject:** Provides rejection reason, rejects request
5. After approval, manually creates actual site visit from Site Visits page

### 8. Security & Authorization
- Client routes check `isClient()` and `hasPageAccess('site_data')`
- Admin routes check `hasAdminAccess()`
- Photo uploads validated (type, size, count)
- CSRF protection on all forms
- Proper foreign key constraints

### 9. UI/UX Enhancements
- Color-coded status badges (warning/success/danger)
- Responsive design for mobile
- Loading states and transitions
- Clear error messages
- Success notifications
- Intuitive button placement as requested

### 10. Database Relationships
```
users (1) ----< (many) site_visit_requests
users (1) ----< (many) site_visit_requests (as reviewer)
```

## Testing Checklist
- [x] Migration runs successfully
- [x] Routes registered correctly
- [x] No syntax errors in PHP files
- [x] No syntax errors in Blade files
- [ ] Client can access request form
- [ ] Client can submit request with photos
- [ ] Admin can view pending requests
- [ ] Admin can approve request
- [ ] Admin can reject request
- [ ] Badge count updates correctly
- [ ] Photo upload and preview works
- [ ] Form validation works
- [ ] Authorization checks work

## Next Steps (Manual Testing Required)
1. Test as client: Create a site visit request
2. Test as admin: View, approve, and reject requests
3. Verify photo uploads work correctly
4. Test responsive design on mobile
5. Verify badge counts update in real-time
6. Test form validation edge cases

## Notes
- Requests are separate from actual site visits (Option 1 approach)
- Admin must manually create site visit after approval
- Photos stored in `storage/app/public/site-visit-requests/`
- Maximum 5 photos per request, 5MB each
- Preferred date must be today or later
