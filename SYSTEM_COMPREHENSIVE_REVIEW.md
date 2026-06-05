# Salenga Farm System - Comprehensive Review

**Date:** May 28, 2026  
**Reviewed By:** Kiro AI Assistant  
**System:** Salenga Farm Plant Inventory & Landscaping Management System

---

## 🎯 SYSTEM OVERVIEW

### Purpose
A comprehensive web-based platform for managing plant inventory, client requests, walk-in sales, and landscaping site visits for Salenga Farm.

### Technology Stack
- **Framework:** Laravel 11.31 (PHP 8.2+)
- **Database:** MySQL/SQLite
- **Frontend:** Bootstrap 5.3, jQuery, Chart.js
- **PDF Generation:** DomPDF
- **Email:** Brevo SMTP + Brevo API
- **Authentication:** Laravel Breeze + Google OAuth (Socialite)
- **Permissions:** Spatie Laravel Permission

---

## 👥 USER ROLES & ACCESS

### 1. **Super Admin** (Full System Access)
- Complete control over all features
- User management (create, edit, delete users)
- System logs access
- All admin features + elevated permissions

### 2. **Admin** (Operations Management)
- Dashboard with analytics
- Inventory management (CRUD plants)
- Client requests management
- Walk-in sales (POS)
- Site visits management
- Plant care library editing
- Cannot manage users or view system logs

### 3. **Client** (Customer Portal)
- Home page (plant browsing)
- Dashboard (personal overview with stats)
- My Requests (view/delete own inquiries)
- Plant Guide (read-only plant care library)
- Site Data (site visit documents - locked until profile complete)
- Profile management
- Request plants (RFQ or simple inquiry)
- Site visit requests

---

## 📊 CORE MODULES

### 1. **PLANT INVENTORY MANAGEMENT**

**Controllers:** `PlantController`, `CategoryController`  
**Model:** `Plant`  
**Routes:** `/plants/*`

**Features:**
- ✅ CRUD operations for plants
- ✅ Plant attributes: name, code, scientific name, category, dimensions (height/spread/spacing), price, stock quantity
- ✅ Photo upload/management
- ✅ Bulk stock updates
- ✅ Category management (persisted)
- ✅ Search functionality
- ✅ Display plants (public showcase with photos)

**Database Fields:**
```
- name, code, scientific_name, description
- category
- height_mm, spread_mm, spacing_mm
- oc (on-center spacing)
- price, cost_per_sqm, pieces_per_sqm, cost_per_mm
- quantity (stock level)
- photo_path
```

**Access:** Admin/Super Admin only

---

### 2. **CLIENT REQUESTS SYSTEM**

**Controllers:** `ClientRequestController`, `UserPlantRequestController`, `RequestFormController`  
**Model:** `PlantRequest`  
**Routes:** `/requests/*`, `/user/plant-request/*`

**Features:**
- ✅ Two request types:
  - **Client RFQ** (Request for Quotation) - Detailed with pricing options
  - **User Inquiry** - Simple plant inquiry
- ✅ Plant selection interface
- ✅ PDF quotation generation
- ✅ Email notifications (Brevo SMTP for PDFs, Brevo API for simple emails)
- ✅ Status tracking: pending, sent, completed
- ✅ Admin can edit request details, pricing, items
- ✅ Clients can view/delete own requests

**Database Fields:**
```
- email, name, phone, address, message
- request_date, due_date
- items_json (array of selected plants with quantities)
- pricing (None, Low cost, High cost)
- status (pending, sent, completed)
- request_type (client, user)
- pdf_path
- response_sent_at, responded_by
```

**Workflow:**
1. Client selects plants from catalog
2. Submits request with contact info
3. Admin receives notification
4. Admin reviews, edits pricing/items
5. Admin generates PDF and sends via email
6. Client receives quotation

**Access:** 
- Clients: Create, view own, delete own
- Admin: View all, edit, send emails, delete

---

### 3. **WALK-IN SALES (POINT OF SALE)**

**Controllers:** `WalkInSalesController`, `WalkInInventoryController`  
**Model:** `Sale`  
**Routes:** `/walk-in/*`

**Features:**
- ✅ Real-time POS interface
- ✅ Plant search and selection
- ✅ Custom attributes (height, spread, spacing per sale)
- ✅ Customer information capture
- ✅ Payment method tracking
- ✅ Automatic inventory deduction
- ✅ Sales records with filtering
- ✅ Sales analytics (percentages, trends)
- ✅ Bulk delete sales records
- ✅ Inventory management interface

**Database Fields:**
```
- plant_id (foreign key)
- quantity, price, total_price
- height, spread, spacing (physical attributes)
- custom_attributes (JSON)
- customer_name, customer_email
- payment_method, notes
- sale_date
```

**Access:** Admin/Super Admin only

---

### 4. **SITE VISITS MANAGEMENT**

**Controllers:** `SiteVisitController`  
**Models:** `SiteVisit`, `SiteVisitRequest`  
**Routes:** `/site-visits/*`, `/client-data/*`

**Features:**
- ✅ Site visit scheduling and tracking
- ✅ GPS coordinates and location mapping
- ✅ Comprehensive site assessment forms:
  - Physical factors
  - Topography
  - Geotechnical/soils
  - Utilities
  - Immediate surroundings
  - Tools checklist
  - Additional services
- ✅ Client data checklist (document uploads by client)
- ✅ Proposal checklist (admin uploads proposals)
- ✅ Client approval workflow
- ✅ Media file uploads (photos, documents)
- ✅ Status tracking: pending, completed, follow_up
- ✅ Client request system (clients can request site visits)
- ✅ Linked to user accounts

**Database Fields:**
```
- user_id (linked client)
- latitude, longitude, location_address
- client, contact_number, email
- job_no, project_code, project_no, location
- landscape_area, site_inspector, visit_date
- topography, geotechnical_soils, utilities (JSON arrays)
- immediate_surroundings, tools_checklist (JSON)
- additional_services (JSON)
- client_data_checklist, proposal_checklist (JSON)
- client_data_statuses, proposal_item_statuses (JSON)
- proposal_approval (JSON)
- client_data_open (boolean - controls client access)
- status, notes, terms_and_conditions
- design_quotation, media_files (JSON)
- physical_factors (JSON)
```

**Workflow:**
1. Client submits site visit request
2. Admin reviews and approves/rejects
3. Admin creates site visit record
4. Admin conducts visit, fills assessment
5. Admin uploads proposal documents
6. Client uploads required documents (client data)
7. Client reviews and approves proposal
8. Status updated to completed

**Access:**
- Clients: Request, view own, upload documents, approve proposals
- Admin: Full CRUD, conduct assessments, manage all visits

---

### 5. **PLANT CARE LIBRARY**

**Controller:** `PlantCareController`  
**Routes:** `/plant-care/*`

**Features:**
- ✅ Educational plant care information
- ✅ Read-only for clients
- ✅ Editable by admins
- ✅ Separate admin management page

**Access:**
- Clients: Read-only (if `hasPageAccess('plant_guide')`)
- Admin: Full edit access
- Super Admin: Full access

---

### 6. **USER MANAGEMENT**

**Controller:** `UserController`  
**Model:** `User`, `RoleRequest`  
**Routes:** `/users/*`

**Features:**
- ✅ User CRUD operations
- ✅ Role management (super_admin, admin, client)
- ✅ Role request system (clients can request role changes)
- ✅ Page access permissions (JSON field)
- ✅ Profile completion tracking
- ✅ Account types: individual, company
- ✅ Avatar upload

**User Fields:**
```
- name, first_name, last_name, email, password
- role (super_admin, admin, client)
- is_client (boolean flag)
- page_access (JSON: ['dashboard', 'my_requests', 'plant_guide', 'site_data'])
- profile_completed (boolean)
- account_type (individual, company)
- contact_number, address, city, province, zip_code
- gender, property_type
- company_name, company_address, company_city, company_province
- company_zip_code, company_contact_person, position
- company_phone_number, business_type, tin, website_socials
- avatar
```

**Access:** Super Admin only

---

### 7. **DASHBOARD & ANALYTICS**

**Controllers:** `DashboardController` (Admin), `UserDashboardController` (Client)  
**Routes:** `/dashboard`, `/client-dashboard`

**Admin Dashboard Features:**
- ✅ Analytics summary (total plants, low stock alerts, recent sales)
- ✅ Inventory overview with stock levels
- ✅ Sales chart (daily/weekly/monthly)
- ✅ Low stock alerts
- ✅ Recent activity feed
- ✅ Quick actions (update stock, view logs)
- ✅ System logs (Super Admin only)

**Client Dashboard Features:**
- ✅ Personal stats (total requests, pending, completed)
- ✅ Recent requests overview
- ✅ Site visit status
- ✅ Profile completion progress
- ✅ Quick actions (new request, view requests)

**Access:**
- Admin Dashboard: Admin/Super Admin
- Client Dashboard: Clients (requires `hasPageAccess('dashboard')`)

---

### 8. **NOTIFICATIONS SYSTEM**

**Controller:** `NotificationController`  
**Model:** `Notification`  
**Routes:** `/notifications/*`

**Features:**
- ✅ Real-time notification bell
- ✅ Unread count badge
- ✅ Notification types:
  - new_request (admin: new client request)
  - request_sent (client: request processed)
  - new_role_request (super admin: role change request)
  - site_visit_created, site_visit_updated
- ✅ Mark as read/unread
- ✅ Mark all as read
- ✅ Delete notifications
- ✅ Delete all notifications
- ✅ Notification links (direct to relevant page)

**Access:** All authenticated users

---

### 9. **AUTHENTICATION & AUTHORIZATION**

**Controllers:** Auth controllers (Laravel Breeze), `SocialiteController`  
**Middleware:** `auth`, `admin`, `can:client-access`, `can:access-admin`

**Features:**
- ✅ Email/password registration and login
- ✅ Google OAuth (Sign in/Sign up with Google)
- ✅ Email verification (MustVerifyEmail)
- ✅ Password reset
- ✅ Remember me
- ✅ Profile management
- ✅ Avatar upload
- ✅ Role-based access control
- ✅ Page-level permissions

**Google OAuth:**
- Creates users with role='client'
- Sets default page_access: ['dashboard', 'my_requests', 'plant_guide', 'site_data']
- Auto-generates password
- Splits name into first_name/last_name

---

### 10. **EMAIL SYSTEM**

**Service:** `BrevoEmailService`  
**Configuration:** Brevo SMTP + Brevo API

**Features:**
- ✅ Dual email system:
  - **Brevo SMTP** (Laravel Mail) - For emails with PDF attachments
  - **Brevo API** (BrevoEmailService) - For simple HTML emails
- ✅ Email types:
  - Plant request quotations (with PDF)
  - Request confirmations
  - Role request notifications
  - Site visit notifications
- ✅ Fallback to logging in local development

**Configuration:**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=farmsalenga@gmail.com
MAIL_PASSWORD=[Brevo SMTP key]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=farmsalenga@gmail.com
BREVO_API_KEY=[Brevo API key]
```

---

## 🗄️ DATABASE STRUCTURE

### Core Tables:
1. **users** - User accounts and profiles
2. **plants** - Plant inventory
3. **plant_requests** - Client requests and inquiries
4. **sales** - Walk-in sales transactions
5. **site_visits** - Site visit records
6. **site_visit_requests** - Client site visit requests
7. **notifications** - User notifications
8. **categories** - Plant categories
9. **display_plants** - Public showcase plants
10. **role_requests** - Role change requests
11. **autofill_cache** - Form autofill data

---

## 🔐 SECURITY FEATURES

### Implemented:
- ✅ CSRF protection (Laravel default)
- ✅ Password hashing (bcrypt)
- ✅ Role-based access control
- ✅ Middleware authorization
- ✅ Email verification
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ File upload validation
- ✅ Environment variable protection (.env in .gitignore)

### Authorization Checks:
- Admin middleware for admin-only routes
- `hasAdminAccess()` for admin features
- `hasPageAccess()` for client page permissions
- `can:client-access` gate for client features
- Owner verification for delete operations (users can only delete own requests)

---

## 📱 RESPONSIVE DESIGN

### Mobile Optimizations:
- ✅ Responsive navbar with mobile menu
- ✅ Compact forms on mobile
- ✅ Touch-friendly buttons
- ✅ Mobile-optimized plant cards
- ✅ Responsive tables
- ✅ Mobile dropdown menus
- ✅ Proper z-index for overlays

### Recent Fixes:
- Login form size reduced on mobile
- Navbar dropdowns fixed (z-index, positioning)
- Plant selection checkbox positioning
- Splash page button visibility
- Login/Register button sizes

---

## 🎨 UI/UX FEATURES

### Design Elements:
- ✅ Bootstrap 5.3 framework
- ✅ Font Awesome icons
- ✅ Custom CSS for branding
- ✅ Chart.js for analytics
- ✅ Loading spinners
- ✅ Toast notifications
- ✅ Modal dialogs
- ✅ Dropdown menus
- ✅ Badge indicators
- ✅ Progress bars (profile completion)
- ✅ Sticky elements
- ✅ Smooth animations

### Color Scheme:
- Primary: Green (farm/nature theme)
- Success: Green
- Warning: Yellow/Orange
- Danger: Red
- Info: Blue

---

## 🚀 DEPLOYMENT

### Current Setup:
- **Production:** DigitalOcean (salengafarm.page)
- **Server:** Nginx + PHP 8.3-FPM
- **Database:** MySQL
- **SSL:** HTTPS enabled
- **Git:** GitHub repository

### Deployment Process:
1. Commit changes locally
2. Push to GitHub
3. SSH into production server
4. `git pull origin main`
5. `php artisan view:clear`
6. `php artisan cache:clear`
7. `php artisan config:clear`
8. `systemctl restart php8.3-fpm`

---

## ⚠️ KNOWN ISSUES & LIMITATIONS

### Current Limitations:
1. ❌ **No Excel/CSV export** - Planned feature, not yet implemented
2. ❌ **No data backup automation** - Manual backups only
3. ❌ **No file encryption** - Uploaded files stored unencrypted
4. ❌ **No AI features** - Chatbot/recommendations not implemented
5. ❌ **No multi-language support** - English only
6. ❌ **No SMS notifications** - Email only
7. ❌ **No payment gateway** - Manual payment tracking only
8. ❌ **No inventory alerts** - Low stock shown but no auto-alerts
9. ❌ **No audit trail** - Limited activity logging
10. ❌ **No API** - No external integrations

### Recent Fixes:
- ✅ Google OAuth page_access now includes all client pages
- ✅ Mobile responsive issues resolved
- ✅ Navbar dropdowns fixed
- ✅ Email system working (Brevo SMTP + API)

---

## 📈 FUTURE ENHANCEMENTS (From Manuscript)

### Recommended:
1. **Excel/CSV Export** - For inventory, sales, requests
2. **Automated Cloud Backup** - Monthly scheduled backups
3. **File Encryption** - AES-256 for sensitive documents
4. **AI Chatbot** - 24/7 automated support
5. **AI Plant Recommendations** - Based on site conditions
6. **Multi-factor Authentication** - Enhanced security
7. **SMS Notifications** - For urgent updates
8. **Payment Gateway Integration** - Online payments
9. **Inventory Alerts** - Auto-notify on low stock
10. **Audit Trail** - Complete activity logging
11. **API Development** - For third-party integrations
12. **Mobile App** - Native iOS/Android apps

---

## 🔍 CODE QUALITY OBSERVATIONS

### Strengths:
- ✅ Well-organized MVC structure
- ✅ Consistent naming conventions
- ✅ Comprehensive validation
- ✅ Good use of Eloquent relationships
- ✅ Proper middleware usage
- ✅ Clean route organization
- ✅ Reusable components
- ✅ Good error handling

### Areas for Improvement:
- ⚠️ Some controllers are large (could be split)
- ⚠️ Limited unit/feature tests
- ⚠️ Some duplicate code in views
- ⚠️ Could use more service classes
- ⚠️ Limited API documentation
- ⚠️ Some hardcoded values (could use config)

---

## 📊 SYSTEM METRICS

### Database Tables: 11
### Controllers: 18
### Models: 11
### Routes: ~100+
### Views: ~50+
### Middleware: 4 custom
### Services: 1 (BrevoEmailService)
### User Roles: 3 (super_admin, admin, client)

---

## ✅ SYSTEM HEALTH CHECK

### Working Features:
- ✅ User authentication (email + Google OAuth)
- ✅ Plant inventory management
- ✅ Client requests system
- ✅ Walk-in sales (POS)
- ✅ Site visits management
- ✅ Email notifications
- ✅ PDF generation
- ✅ File uploads
- ✅ Notifications system
- ✅ Dashboard analytics
- ✅ Profile management
- ✅ Role-based access control
- ✅ Mobile responsive design

### Production Status:
- ✅ Deployed and accessible
- ✅ SSL certificate active
- ✅ Email system configured
- ✅ Google OAuth configured
- ✅ Database connected
- ✅ File storage working

---

## 🎯 NEXT STEPS

### Immediate Priorities:
1. **Implement Excel/CSV Export** - For admin reporting
2. **Add automated backups** - Data protection
3. **Enhance testing** - Unit and feature tests
4. **Optimize performance** - Query optimization, caching
5. **Improve documentation** - API docs, user manual

### Long-term Goals:
1. AI-powered features (chatbot, recommendations)
2. Mobile app development
3. Payment gateway integration
4. Advanced analytics and reporting
5. Multi-language support

---

**End of Comprehensive Review**
