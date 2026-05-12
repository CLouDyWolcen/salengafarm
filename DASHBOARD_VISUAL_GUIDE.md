# Client Dashboard - Visual Guide

## **Navigation Structure**

```
┌─────────────────────────────────────────────────────────────┐
│  Salenga Farm                                    [Profile ▼]│
├─────────────────────────────────────────────────────────────┤
│  [Home] [Dashboard] [My Requests] [Plant Guide] [Site Data] │
└─────────────────────────────────────────────────────────────┘
```

---

## **1. Dashboard (Overview) - NEW**
**Route:** `/client-dashboard`

```
┌─────────────────────────────────────────────────────────────┐
│  📊 Welcome back, John! 👋                                   │
│  Last login: May 7, 2026 at 2:30 PM                        │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │ 💬 Plant     │  │ 📋 Site Visit│  │ 📁 Site      │     │
│  │ Requests     │  │ Requests     │  │ Visits       │     │
│  │              │  │              │  │              │     │
│  │     5        │  │     3        │  │     4        │     │
│  │ 2 responded  │  │ 1 approved   │  │ 2 active     │     │
│  │ 3 pending    │  │ 1 pending    │  │ 2 completed  │     │
│  │              │  │              │  │              │     │
│  │ [View All →] │  │ [View All →] │  │ [View All →] │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ⚠️ Pending Actions                                   │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ 🕐 Site Visit Request Pending                       │   │
│  │    Your request for 123 Main St is awaiting        │   │
│  │    approval                                         │   │
│  │    Submitted 2 days ago          [View Request →]  │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ 🕐 Recent Activity                                   │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ ✅ Plant Inquiry #45                                │   │
│  │    Your inquiry received a response                 │   │
│  │    2 days ago                    [View →]           │   │
│  │                                                      │   │
│  │ ⏳ Site Visit Request                               │   │
│  │    Request submitted and pending review             │   │
│  │    3 days ago                    [View →]           │   │
│  │                                                      │   │
│  │ ℹ️ Site Visit Scheduled                             │   │
│  │    Visit scheduled for May 15, 2026                 │   │
│  │    1 week ago                    [View →]           │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ ⚡ Quick Actions                                     │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ [🌱 Submit Plant Inquiry] [📋 Request Site Visit]   │   │
│  │ [📋 View My Requests]     [📁 View Site Data]       │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ 💡 Tips & Resources                                  │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ • Browse our plant catalog to submit inquiries      │   │
│  │ • Request a site visit for landscaping advice       │   │
│  │ • Check Plant Guide for care instructions           │   │
│  │ • We'll email you when requests are processed       │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## **2. My Requests - NEW**
**Route:** `/my-requests`

```
┌─────────────────────────────────────────────────────────────┐
│  📋 My Requests                                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ Plant Inquiries & RFQ                               │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ ID  │ Request Date │ Status     │ Actions          │   │
│  ├─────────────────────────────────────────────────────┤   │
│  │ #45 │ May 5, 2026  │ Responded  │ 👁️ 📥 🗑️        │   │
│  │ #44 │ May 3, 2026  │ Pending    │ 🗑️              │   │
│  │ #43 │ May 1, 2026  │ Responded  │ 👁️ 📥 🗑️        │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                              │
│  Sidebar:                                                   │
│  [🌱 Submit Plant Inquiry]                                  │
│  [📊 Back to Dashboard]                                     │
│                                                              │
│  About Plant Requests:                                      │
│  • Submit inquiries from the Home page                      │
│  • We'll email you when quotation is ready                  │
│  • Download RFQ documents once responded                    │
│  • Track all your plant requests here                       │
└─────────────────────────────────────────────────────────────┘
```

---

## **3. Site Data (Existing - Will Add Tabs Next)**
**Route:** `/client-data`

```
┌─────────────────────────────────────────────────────────────┐
│  📁 Client Data                    [Request Site Visit] ✅   │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  [My Requests] [Site Visits]  ← TABS (To be added)         │
│                                                              │
│  Current table showing site visits...                       │
└─────────────────────────────────────────────────────────────┘
```

---

## **Comparison: Old vs New**

### **OLD Structure:**
```
❌ Dashboard = Just plant inquiries table
   (Not really a dashboard)
```

### **NEW Structure:**
```
✅ Dashboard = Overview with stats, activity, actions
✅ My Requests = Plant inquiries table
✅ Site Data = Site visit requests + visits (next step)
```

---

## **Color Scheme**

**Status Colors:**
- 🟢 Green (Success): Responded, Approved, Completed
- 🟡 Yellow (Warning): Pending, Awaiting
- 🔵 Blue (Info): In Progress, Scheduled
- 🔴 Red (Danger): Rejected, Overdue

**Card Colors:**
- Summary Cards: White with green left border
- Pending Actions: Yellow background
- Recent Activity: White with hover effect
- Quick Actions: White with green hover

---

## **Icons Used**

| Icon | Usage |
|------|-------|
| 📊 | Dashboard |
| 📋 | My Requests / Lists |
| 🌱 | Plant / Seedling |
| 📁 | Site Data / Folder |
| 🏠 | Home |
| 💬 | Inquiries / Messages |
| 📅 | Calendar / Schedule |
| 🗺️ | Map / Site Visits |
| ⚡ | Quick Actions |
| 💡 | Tips / Info |
| ⚠️ | Pending / Warning |
| ✅ | Success / Completed |
| ⏳ | Pending / Waiting |
| 👁️ | View |
| 📥 | Download |
| 🗑️ | Delete |

---

## **Responsive Behavior**

### **Desktop (> 992px):**
- Dashboard: 2-column layout (8-4 split)
- Summary cards: 3 cards in a row
- Quick actions: 2x2 grid

### **Tablet (768px - 992px):**
- Dashboard: 2-column layout
- Summary cards: 3 cards in a row (smaller)
- Quick actions: 2x2 grid

### **Mobile (< 768px):**
- Dashboard: Single column
- Summary cards: Stack vertically
- Quick actions: 2x2 grid (smaller buttons)
- Navbar: Hamburger menu

---

## **User Journey**

```
1. Client logs in
   ↓
2. Lands on Home (plant catalog)
   ↓
3. Clicks "Dashboard" in navbar
   ↓
4. Sees overview with stats
   ↓
5. Can click:
   - "View All" on any card → Goes to specific page
   - Quick action buttons → Goes to action page
   - Recent activity links → Goes to detail page
   ↓
6. Clicks "My Requests" in navbar
   ↓
7. Sees all plant inquiries
   ↓
8. Can view responses, download RFQ, delete
```

---

## **Next Implementation: Site Data Tabs**

```
Site Data Page (To be updated):
┌─────────────────────────────────────────────────────────────┐
│  📁 Site Data                      [Request Site Visit] ✅   │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  [My Requests (1)] [Site Visits (4)]  ← NEW TABS           │
│   ⚠️ Active         📁 Tab                                  │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  MY REQUESTS TAB:                                           │
│  ┌───────────────────────────────────────────────────────┐ │
│  │ ✅ Request Approved                                    │ │
│  │ Property: 123 Main St                                 │ │
│  │ Preferred Date: May 15, 2026                          │ │
│  │ Admin Notes: "We'll contact you soon"                 │ │
│  └───────────────────────────────────────────────────────┘ │
│                                                              │
│  SITE VISITS TAB:                                           │
│  (Current table showing actual site visits)                 │
└─────────────────────────────────────────────────────────────┘
```

This completes the full restructure! 🎉
