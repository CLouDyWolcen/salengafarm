# Admin & Super Admin Dashboard Analysis

## Overview
The admin/super admin uses a **sidebar navigation** (different from user's top navbar). The sidebar is located at `resources/views/layouts/sidebar.blade.php`.

## Sidebar Menu Structure

### Common Items (All Users):
- **Home** - Links to homepage
- **Dashboard** - User/Admin dashboard

### Admin & Super Admin Items:
- **Inventory** - Plant management (`/plants`)
- **Point-of-Sale** - Walk-in sales (`/walk-in` or `/walk-in/inventory` for super admin)
- **Requests** - Client requests (`/requests`)
- **Site Visits** - Site visit management (`/site-visits`)

### Super Admin Only:
- **Users** - User management (`/users`)

### Client Only:
- **Client Data** - View site visit data (`/client-data`)

### Bottom Section (All):
- **Notifications** - Bell icon with badge (admin only)
- **Profile Card** - Avatar + name, links to profile edit
- **Logout** - Red logout button

## Mobile Sidebar Behavior
- **Toggle Button**: Green hamburger (☰) in top right
- **Slide-out**: Sidebar slides from right side
- **Overlay**: Dark overlay behind sidebar
- **Fixed positioning**: Sidebar stays on screen when scrolling

## Key Components Identified

### 1. Layout Structure
- **Sidebar**: Includes navigation menu (from `layouts.sidebar`)
- **Main Content Area**: Three-column layout with responsive design
- **Mobile Toggle**: Sidebar toggle button for mobile devices

### 2. Summary Cards (Top Row)
- **Total Plants in Stock**: Shows `$totalStock` count
- **Low Stock Items**: Shows count of `$lowStockItems`

### 3. Three-Column Layout

#### Left Column (col-md-3):
1. **Analytics Summary Card**
   - Total Revenue (₱)
   - Plants Sold (quantity)
   - Top Category
   - Average Turnover

2. **Low Stock Alerts Card**
   - Lists plants with low stock
   - Shows plant name, category, and quantity left
   - Badge with warning color

#### Middle Column (col-md-6):
**Chart Container with Tabs**:
1. **Stock Distribution** (Pie Chart)
   - Shows distribution of plants by category
   - Canvas ID: `stockDistributionChart`

2. **Sales by Category** (Bar Chart)
   - Period selector: 7/30/90/365 days
   - Type selector: Quantity/Revenue
   - Canvas ID: `salesDistributionChart`

3. **Sales Trends** (Line Chart)
   - Period selector: 7/30/90/365 days
   - Metric selector: Daily Quantity/Revenue
   - Canvas ID: `salesTrendsChart`

#### Right Column (col-md-3):
1. **Quick Actions Card**
   - For Admin: "Update Stock" button (opens modal)
   - For Super Admin: "System Logs" and "Sales Records" buttons

2. **Recent Plants Card**
   - Lists recently added plants
   - Shows plant name, "Added X ago", and stock quantity badge

### 4. Update Stock Modal
- **Category Sidebar**: Filter by plant category (All, Shrub, Herbs, Palm, Tree, Grass, Bamboo, Fertilizer)
- **Search Bar**: Search plants by name
- **Scrollable Table**: Shows plant name, category, and stock input field
- **Sticky Header**: Table header stays visible when scrolling

### 5. Role-Based Features
- **Admin**: Can update stock
- **Super Admin**: Can view system logs and sales records (no stock update button)

### 6. Styling & Assets
- Bootstrap 5.3.0
- Font Awesome 6.0.0
- Chart.js for analytics charts
- Custom CSS files:
  - `sidebar.css`
  - `inventory.css`
  - `dashboard.css`
  - `loading.css`
  - `push-notifications.css`

### 7. Interactive Features
- Loading overlay with spinner
- Instruction overlay system (tooltips for guidance)
- Category filtering in Update Stock modal
- Search functionality for plants
- Chart period/metric selectors
- Responsive design with mobile sidebar toggle

## Data Variables Used
- `$totalStock` - Total plant count
- `$lowStockItems` - Collection of low stock plants
- `$salesByCategory` - Sales data grouped by category
- `$inventoryTurnover` - Turnover rates
- `$plants` - All plants for stock update modal

## Mobile Considerations
- Sidebar collapses on mobile with toggle button
- Three-column layout likely stacks on mobile
- Modal may need responsive improvements
- Charts may need mobile-specific sizing

## Next Steps for Mobile Optimization
1. Analyze how the three-column layout behaves on mobile
2. Check if cards are properly responsive
3. Review Update Stock modal on mobile
4. Test chart responsiveness
5. Ensure Quick Actions buttons are accessible on mobile
