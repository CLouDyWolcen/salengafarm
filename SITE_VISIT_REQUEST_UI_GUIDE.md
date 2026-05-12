# Site Visit Request - UI Placement Guide

## Button Locations

### 1. Client Side - Site Data Page
**Location:** Top right corner of the page
**File:** `resources/views/client-data/index.blade.php`

```
┌─────────────────────────────────────────────────────────────┐
│  📁 Client Data                    [Request Site Visit] ✅   │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  Table of site visits...                                     │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

**Button Details:**
- Color: Green (`btn-success`)
- Icon: Calendar plus (`fas fa-calendar-plus`)
- Text: "Request Site Visit"
- Action: Opens request form

---

### 2. Admin Side - Site Visits Page
**Location:** Left of "Add New Site Visit" button
**File:** `resources/views/site-visits/index.blade.php`

```
┌─────────────────────────────────────────────────────────────┐
│  🗺️ Site Visits Management                                   │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│                    [Site Visit Requests (3)] [Add New Site Visit] │
│                           ℹ️ Info                  ✅ Green    │
├─────────────────────────────────────────────────────────────┤
│  Map Section...                                              │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

**Button Details:**
- Color: Info blue (`btn-info`)
- Icon: Inbox (`fas fa-inbox`)
- Text: "Site Visit Requests"
- Badge: Red circle with pending count (if > 0)
- Action: Opens requests management page

---

## Request Form Layout

### Client Request Form
**Route:** `/site-visit-requests/create`

```
┌─────────────────────────────────────────────────────────────┐
│  📅 Request Site Visit              [Back to Site Data]      │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  📅 Visit Details                                            │
│  ├─ Preferred Date *                                         │
│  └─ Preferred Time                                           │
│                                                               │
│  📍 Property Information                                     │
│  ├─ Property Address *                                       │
│  ├─ Property Size                                            │
│  └─ Current Condition                                        │
│                                                               │
│  📋 Project Description                                      │
│  ├─ Describe Your Project *                                 │
│  └─ Special Requirements                                     │
│                                                               │
│  📷 Property Photos (Optional)                               │
│  └─ Upload up to 5 photos                                   │
│                                                               │
│                              [Cancel] [Submit Request] ✅     │
└─────────────────────────────────────────────────────────────┘
```

---

## Admin Requests Page Layout

### Site Visit Requests Management
**Route:** `/site-visit-requests`

```
┌─────────────────────────────────────────────────────────────┐
│  📥 Site Visit Requests              [Back to Site Visits]   │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  [Pending (3)] [Approved (5)] [Rejected (1)]                │
│   ⚠️ Active    ✅ Tab        ❌ Tab                          │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ 👤 John Doe                              [Pending] ⚠️  │  │
│  │ 📧 john@example.com  📞 123-456-7890                  │  │
│  │                                                        │  │
│  │ 📅 Preferred: May 15, 2026 at 10:00 AM               │  │
│  │ 📍 Address: 123 Main St, City                        │  │
│  │ 📏 Size: 500 sqm                                      │  │
│  │ ℹ️ Condition: Bare Land                               │  │
│  │                                                        │  │
│  │ 📋 Project: Need landscaping for new home...         │  │
│  │ ⚠️ Requirements: Access limited on weekends...        │  │
│  │                                                        │  │
│  │ 📷 Photos: [img] [img] [img]                         │  │
│  │                                                        │  │
│  │ 🕐 Requested: May 7, 2026 2:30 PM                    │  │
│  │                                                        │  │
│  │                              [Approve ✅] [Reject ❌]  │  │
│  └───────────────────────────────────────────────────────┘  │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Color Scheme

### Status Colors
- **Pending:** Yellow/Warning (`bg-warning`)
- **Approved:** Green/Success (`bg-success`)
- **Rejected:** Red/Danger (`bg-danger`)

### Button Colors
- **Request Site Visit (Client):** Green (`btn-success`)
- **Site Visit Requests (Admin):** Info Blue (`btn-info`)
- **Approve:** Green (`btn-success`)
- **Reject:** Red (`btn-danger`)
- **Cancel/Back:** Gray (`btn-secondary` or `btn-outline-secondary`)

### Badge Colors
- **Pending Count:** Red (`bg-danger`) - on Site Visits page button
- **Tab Counts:** Matches status color

---

## Icons Used

| Icon | Usage | Class |
|------|-------|-------|
| 📅 | Calendar/Date | `fas fa-calendar-plus` |
| 📥 | Inbox | `fas fa-inbox` |
| 👤 | User | `fas fa-user` |
| 📧 | Email | `fas fa-envelope` |
| 📞 | Phone | `fas fa-phone` |
| 📍 | Location | `fas fa-map-marker-alt` |
| 📏 | Size/Ruler | `fas fa-ruler-combined` |
| ℹ️ | Info | `fas fa-info-circle` |
| 📋 | Clipboard | `fas fa-clipboard-list` |
| ⚠️ | Warning | `fas fa-exclamation-triangle` |
| 📷 | Camera | `fas fa-camera` |
| 🕐 | Clock | `fas fa-clock` |
| ✅ | Check | `fas fa-check` |
| ❌ | Times/Close | `fas fa-times` |
| ✔️ | Check Circle | `fas fa-check-circle` |
| ⭕ | Times Circle | `fas fa-times-circle` |
| ⬅️ | Arrow Left | `fas fa-arrow-left` |
| 📤 | Paper Plane | `fas fa-paper-plane` |

---

## Responsive Behavior

### Desktop (> 768px)
- Buttons side by side
- Full card layout
- Photo thumbnails in row

### Mobile (≤ 768px)
- Buttons stack vertically
- Compact card layout
- Photo thumbnails wrap
- Sidebar toggle visible

---

## User Flow Diagram

```
CLIENT FLOW:
┌─────────────┐
│ Site Data   │
│   Page      │
└──────┬──────┘
       │ Click "Request Site Visit"
       ▼
┌─────────────┐
│  Request    │
│   Form      │
└──────┬──────┘
       │ Submit
       ▼
┌─────────────┐
│ Confirmation│
│  Message    │
└──────┬──────┘
       │ Back to Site Data
       ▼
┌─────────────┐
│ Site Data   │
│   Page      │
└─────────────┘

ADMIN FLOW:
┌─────────────┐
│ Site Visits │
│    Page     │
└──────┬──────┘
       │ Click "Site Visit Requests (3)"
       ▼
┌─────────────┐
│  Requests   │
│    List     │
└──────┬──────┘
       │ Review Request
       ▼
┌─────────────┐
│ Approve or  │
│   Reject    │
└──────┬──────┘
       │ Confirm Action
       ▼
┌─────────────┐
│ Updated     │
│   List      │
└──────┬──────┘
       │ Manually create site visit (if approved)
       ▼
┌─────────────┐
│ Site Visits │
│    Page     │
└─────────────┘
```
