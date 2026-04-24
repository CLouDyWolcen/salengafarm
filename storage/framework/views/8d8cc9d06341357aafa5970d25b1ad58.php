<?php $__env->startSection('title', 'Point of Sale - Salenga Farm'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Improve input number styles */
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    
    input[type="number"]::-webkit-inner-spin-button, 
    input[type="number"]::-webkit-outer-spin-button { 
        -webkit-appearance: inner-spin-button;
        opacity: 1;
        height: 25px;
        position: absolute;
        right: 0;
        top: 0;
    }
    
    /* Editable fields styling */
    .editable-field {
        border: 1px solid #adb5bd !important;
        background-color: #f8f9fa;
        transition: all 0.2s ease-in-out;
    }
    
    .editable-field:focus {
        background-color: #fff;
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Improve table styling */
    .table {
        vertical-align: middle;
    }
    
    .table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-color: #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .table-bordered td {
        border-color: #dee2e6;
    }
    
    .table-bordered {
        border-color: #dee2e6;
    }
    
    /* Force table scrollable - Plant Selection specific */
    .col-lg-8 .card-body .table-responsive {
        max-height: 600px !important;
        overflow-y: auto !important;
        display: block !important;
        border: 1px solid #dee2e6;
    }
    
    .col-lg-8 .card-body .table-responsive table {
        margin-bottom: 0 !important;
    }
    
    /* Cart item quantity styling */
    .item-quantity {
        padding-right: 20px !important;
        text-align: center;
        border: 1px solid #adb5bd !important;
        font-weight: 500;
    }

    /* New styles for improved cart layout */
    .cart-section {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .cart-items-container {
        max-height: 250px;
        overflow-y: auto;
        margin-bottom: 10px;
    }
    
    .cart-footer {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-top: auto;
    }
    
    .action-buttons {
        position: sticky;
        bottom: 0;
        background-color: #fff;
        padding: 15px 0;
        border-top: 1px solid #dee2e6;
        z-index: 100;
    }

    .cart-card {
        height: calc(100vh - 120px);
        display: flex;
        flex-direction: column;
    }

    .cart-body {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .customer-info {
        margin-top: 10px;
    }
    
    /* Fix for long container text in tables */
    .table td, .table th {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Add tooltip capability for truncated text */
    .truncate-text {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }
    
    /* Recent sales and low stock tables */
    #salesRecordsTable th, 
    #salesRecordsTable td,
    #lowStockTable th,
    #lowStockTable td {
        font-size: 0.85rem;
        padding: 0.5rem;
    }

    /* POS Compact Styles */
    .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }
    .mb-4 {
        margin-bottom: 1.5rem !important;
    }
    h1.mb-0 {
        font-size: 1.75rem;
        font-weight: 600;
    }
    .btn {
        font-size: 0.8rem;
        padding: 0.3rem 0.8rem;
    }
    .card-header {
        padding: 0.75rem 1.25rem;
    }
    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
    }
    .table th,
    .table td {
        padding: 0.5rem;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .form-control,
    .input-group-text {
        font-size: 0.85rem;
    }
    .editable-field,
    .form-control-sm {
        padding: 0.2rem 0.3rem;
        font-size: 0.7rem;
    }
    .add-to-cart-btn {
        padding: 0.3rem 0.7rem;
    }
    .cart-card {
        height: calc(100vh - 100px);
    }
    .customer-info .form-label {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }
    .customer-info .form-control {
        font-size: 0.85rem;
        padding: 0.3rem 0.5rem;
    }
    #cart-table-body td {
        padding: 0.4rem;
        font-size: 0.85rem;
    }
    .item-quantity {
        width: 60px !important;
        height: auto;
    }
    
    /* Price input width fix */
    .price-input {
        min-width: 100px !important;
        width: 100px !important;
    }
    
    /* Improved input sizing for numeric fields - reduced for 5 digits */
    .editable-field {
        min-width: 70px !important;
        width: 70px !important;
        max-width: 70px !important;
        font-size: 0.7rem !important;
        padding: 0.2rem 0.3rem !important;
        text-align: center;
    }
    
    .price-field {
        min-width: 80px !important;
        width: 80px !important;
        max-width: 80px !important;
        font-size: 0.7rem !important;
        padding: 0.2rem 0.3rem !important;
        text-align: center;
    }
    
    /* Current Sale improvements */
    .current-sale-compact {
        font-size: 0.85rem;
    }
    
    /* Cart table improvements */
    #cart-table-body .plant-name {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.8rem;
    }
    
    #cart-table-body .qty-input {
        width: 50px !important;
        min-width: 50px !important;
        text-align: center;
        padding: 0.2rem 0.3rem;
    }
    
    #cart-table-body .price-display {
        min-width: 70px;
        text-align: right;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    #cart-table-body .total-display {
        min-width: 80px;
        text-align: right;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    /* Table column sizing for better layout */
    .plant-selection-table {
        table-layout: fixed;
        width: 100%;
    }
    
    .plant-selection-table th:nth-child(1) { width: 18%; } /* Name */
    .plant-selection-table th:nth-child(2) { width: 10%; } /* Code */
    .plant-selection-table th:nth-child(3) { width: 14%; } /* Height */
    .plant-selection-table th:nth-child(4) { width: 14%; } /* Spread */
    .plant-selection-table th:nth-child(5) { width: 14%; } /* Spacing */
    .plant-selection-table th:nth-child(6) { width: 16%; } /* Price */
    .plant-selection-table th:nth-child(7) { width: 14%; } /* Actions */
    
    /* Fix for Plant Selection table cells */
    .plant-selection-table td {
        vertical-align: middle;
        padding: 0.5rem 0.3rem;
    }
    
    .plant-selection-table th {
        vertical-align: middle;
        padding: 0.5rem 0.3rem;
        font-size: 0.75rem;
        text-align: center;
        white-space: normal;
        line-height: 1.2;
        font-weight: 600;
    }
    
    /* Plant name column - left aligned */
    .plant-selection-table td:nth-child(1),
    .plant-selection-table th:nth-child(1) {
        text-align: left;
        padding-left: 0.75rem;
    }
    
    /* Code column - center aligned */
    .plant-selection-table td:nth-child(2),
    .plant-selection-table th:nth-child(2) {
        text-align: center;
    }
    
    /* Input fields - center aligned */
    .plant-selection-table td:nth-child(3),
    .plant-selection-table td:nth-child(4),
    .plant-selection-table td:nth-child(5),
    .plant-selection-table td:nth-child(6) {
        text-align: center;
    }
    
    /* Actions column - center aligned */
    .plant-selection-table td:nth-child(7),
    .plant-selection-table th:nth-child(7) {
        text-align: center;
    }
    
    /* Add button styling in plant selection */
    .plant-selection-table .add-to-cart-btn {
        white-space: nowrap;
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
        width: 100%;
        max-width: 90px;
        background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%);
        border: none;
        box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);
        transition: all 0.3s ease;
    }
    
    .plant-selection-table .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
        background: linear-gradient(135deg, #4caf50 0%, #43a047 100%);
    }
    
    /* Input fields in table - make them fit better */
    .plant-selection-table .editable-field {
        width: 100% !important;
        max-width: 100px;
        min-width: 70px;
        margin: 0 auto;
        display: block;
    }
    
    .plant-selection-table .price-field {
        width: 100% !important;
        max-width: 110px;
        min-width: 80px;
        margin: 0 auto;
        display: block;
    }
    
    /* Cart table - Compact auto-width layout */
    .cart-table {
        table-layout: auto;
        width: auto;
        margin: 0 auto;
    }
    
    .cart-items-container {
        overflow-y: auto;
        overflow-x: auto; /* Changed from hidden to auto for mobile scroll */
    }
    
    /* Set specific widths for columns to keep them compact */
    .cart-table th:nth-child(1), .cart-table td:nth-child(1) { width: 120px; } /* Plant */
    .cart-table th:nth-child(2), .cart-table td:nth-child(2) { width: 50px; }  /* Qty */
    .cart-table th:nth-child(3), .cart-table td:nth-child(3) { width: 70px; }  /* Price */
    .cart-table th:nth-child(4), .cart-table td:nth-child(4) { width: 70px; }  /* Total */
    .cart-table th:nth-child(5), .cart-table td:nth-child(5) { width: 40px; }  /* Delete */
    
    /* Make everything much smaller for better fit */
    .cart-table th {
        font-size: 0.65rem !important;
        padding: 0.2rem 0.1rem !important;
        font-weight: 600;
    }
    
    .cart-table td {
        font-size: 0.65rem !important;
        padding: 0.2rem 0.1rem !important;
    }
    
    /* Smaller quantity input */
    .cart-table .qty-input {
        width: 35px !important;
        height: 24px !important;
        font-size: 0.6rem !important;
        padding: 0.1rem 0.2rem !important;
    }
    
    /* Smaller delete button */
    .cart-table .remove-item-btn {
        padding: 0.1rem 0.2rem !important;
        font-size: 0.6rem !important;
    }
    
    .current-sale-compact .card-header {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    
    .current-sale-compact .form-label {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    
    .current-sale-compact .form-control,
    .current-sale-compact .form-select {
        font-size: 0.8rem;
        padding: 0.3rem 0.5rem;
        height: auto;
    }
    
    .current-sale-compact .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .current-sale-compact .customer-info {
        padding: 0.75rem;
    }
    
    .current-sale-compact .cart-items-container {
        max-height: 200px;
    }
    
    .current-sale-compact .subtotal-section {
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
    }
    
    .current-sale-compact .subtotal-section h6 {
        font-size: 1rem;
        margin: 0;
    }
    
    .current-sale-compact .empty-cart {
        padding: 2rem 1rem;
    }
    
    .current-sale-compact .empty-cart i {
        font-size: 2rem;
    }
    
    
    /* Quantity input specific styling */
    .cart-table .item-quantity {
        width: 45px !important;
        height: 28px;
        padding: 0.2rem 0.3rem;
        text-align: center;
        font-size: 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 3px;
    }
    
    /* Remove button styling */
    .cart-table .remove-item-btn {
        padding: 0.15rem 0.3rem;
        font-size: 0.7rem;
        border-radius: 3px;
    }
    
    /* Subtotal section improvements */
    .subtotal-section {
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
        padding: 0.75rem 1rem;
    }
    
    .subtotal-section h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    
    /* Customer info section improvements */
    .customer-info {
        padding: 0.75rem 1rem;
        background-color: #fff;
    }
    
    .customer-info .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #495057;
    }
    
    .customer-info .form-control,
    .customer-info .form-select {
        font-size: 0.8rem;
        padding: 0.35rem 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    
    .customer-info textarea.form-control {
        resize: none;
        min-height: 60px;
    }
    
    /* Action buttons improvements */
    .action-buttons {
        padding: 0.75rem 1rem;
        background-color: #fff;
        border-top: 2px solid #dee2e6;
    }
    
    .action-buttons .btn {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.5rem 0.8rem;
        border-radius: 4px;
    }
    
    /* Cart items container improvements */
    .cart-items-container {
        max-height: 200px;
        overflow-y: auto;
        padding: 0.5rem;
        background-color: #fff;
    }
    
    /* Scrollbar styling for cart items */
    .cart-items-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .cart-items-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .cart-items-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .cart-items-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Ensure FontAwesome spin animation works */
    .fa-spin {
        animation: fa-spin 1s infinite linear;
    }
    
    @keyframes fa-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Alternative: Simple pulsing effect for loading states */
    .loading-pulse {
        animation: loading-pulse 1.5s infinite;
    }
    
    @keyframes loading-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Sales Records Modal - Ensure checkboxes are visible and table is scrollable */
    #salesRecordsModal .table-responsive {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
    }
    
    #salesRecordsModal .table thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
    }
    
    /* Ensure Delete Selected button is clickable above table */
    #salesRecordsModal .modal-body > .row {
        position: relative;
        z-index: 20;
    }
    
    #bulk-delete-btn {
        position: relative;
        z-index: 25;
    }
    
    #salesRecordsModal .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        margin: 0;
        border: 1px solid #000 !important;
        background-color: #fff;
    }
    
    #salesRecordsModal .form-check-input:checked {
        background-color: #4caf50;
        border-color: #000 !important;
    }
    
    #salesRecordsModal .record-checkbox {
        display: inline-block;
        vertical-align: middle;
    }
    
    #salesRecordsModal tbody td:first-child {
        text-align: center;
        vertical-align: middle;
    }
    
    #salesRecordsModal .pagination {
        margin: 0;
    }
    
    #sales-records-pagination {
        padding: 10px 0;
        background-color: #fff;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        /* Sidebar toggle button */
        #sidebarToggle {
            display: flex !important;
        }
        
        /* Make table horizontally scrollable */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            display: block !important;
        }
        
        /* Force table to maintain ALL 7 columns structure */
        .plant-selection-table {
            min-width: 700px !important;
            display: table !important;
            width: auto !important;
            table-layout: auto !important;
        }
        
        .plant-selection-table thead,
        .plant-selection-table tbody {
            display: table-row-group !important;
        }
        
        .plant-selection-table tr {
            display: table-row !important;
        }
        
        /* Force ALL columns to display - Name, Code, Height, Spread, Spacing, Price, Actions */
        .plant-selection-table th,
        .plant-selection-table td {
            display: table-cell !important;
            padding: 0.4rem 0.3rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap !important;
            vertical-align: middle !important;
        }
        
        /* Make header cells equal width for Height, Spread, Spacing */
        .plant-selection-table th:nth-child(3),
        .plant-selection-table th:nth-child(4),
        .plant-selection-table th:nth-child(5) {
            width: 75px !important;
            min-width: 75px !important;
            max-width: 75px !important;
            padding: 0.4rem 0.2rem !important;
        }
        
        /* Make data cells equal width for Height, Spread, Spacing */
        .plant-selection-table td:nth-child(3),
        .plant-selection-table td:nth-child(4),
        .plant-selection-table td:nth-child(5) {
            width: 75px !important;
            min-width: 75px !important;
            max-width: 75px !important;
            padding: 0.4rem 0.2rem !important;
        }
        
        /* Ensure all 7 columns are visible */
        .plant-selection-table th:nth-child(1),
        .plant-selection-table td:nth-child(1),
        .plant-selection-table th:nth-child(2),
        .plant-selection-table td:nth-child(2),
        .plant-selection-table th:nth-child(3),
        .plant-selection-table td:nth-child(3),
        .plant-selection-table th:nth-child(4),
        .plant-selection-table td:nth-child(4),
        .plant-selection-table th:nth-child(5),
        .plant-selection-table td:nth-child(5),
        .plant-selection-table th:nth-child(6),
        .plant-selection-table td:nth-child(6),
        .plant-selection-table th:nth-child(7),
        .plant-selection-table td:nth-child(7) {
            display: table-cell !important;
        }
        
        /* Smaller input fields - consistent sizing for all columns */
        .editable-field,
        .form-control-sm {
            padding: 0.15rem 0.25rem !important;
            font-size: 0.65rem !important;
            min-width: 60px !important;
            width: 70px !important;
            margin: 0 !important;
        }
        
        /* Ensure table cells have no extra padding */
        .plant-selection-table td:nth-child(3),
        .plant-selection-table td:nth-child(4),
        .plant-selection-table td:nth-child(5) {
            padding: 0.4rem 0.2rem !important;
        }
        
        /* Compact Add button */
        .add-to-cart-btn {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.7rem !important;
            white-space: nowrap !important;
        }
        
        .add-to-cart-btn i {
            font-size: 0.65rem !important;
        }
        
        /* Keep Records and Inventory buttons horizontal on mobile */
        .btn-group {
            flex-direction: row !important;
            width: auto !important;
            gap: 0.5rem !important;
        }
        
        .btn-group .btn {
            width: auto !important;
            margin: 0 !important;
            flex: 1 !important;
        }
        
        /* Compact header */
        h1.mb-0 {
            font-size: 1.3rem !important;
        }
        
        /* Reduce card padding */
        .card-header {
            padding: 0.5rem 0.75rem !important;
        }
        
        .card-header h5 {
            font-size: 0.9rem !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
        }
        
        /* Adjust header layout for mobile */
        .d-flex.justify-content-between {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        
        /* Current Sale Cart Table - Mobile fixes */
        /* CRITICAL: Override all table CSS with highest specificity */
        .cart-items-container .cart-table.table.table-sm.table-bordered {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed !important;
            display: table !important;
            margin: 0 !important;
        }
        
        .cart-items-container .cart-table.table thead,
        .cart-items-container .cart-table.table tbody {
            display: table-row-group !important;
        }
        
        .cart-items-container .cart-table.table tr {
            display: table-row !important;
            width: 100% !important;
        }
        
        .cart-items-container .cart-table.table th,
        .cart-items-container .cart-table.table td {
            display: table-cell !important;
        }
        
        /* Force equal column distribution */
        .cart-items-container .cart-table.table th:nth-child(1), 
        .cart-items-container .cart-table.table td:nth-child(1) { 
            width: 30% !important;
        }
        
        .cart-items-container .cart-table.table th:nth-child(2), 
        .cart-items-container .cart-table.table td:nth-child(2) { 
            width: 15% !important;
        }
        
        .cart-items-container .cart-table.table th:nth-child(3), 
        .cart-items-container .cart-table.table td:nth-child(3) { 
            width: 20% !important;
        }
        
        .cart-items-container .cart-table.table th:nth-child(4), 
        .cart-items-container .cart-table.table td:nth-child(4) { 
            width: 20% !important;
        }
        
        .cart-items-container .cart-table.table th:nth-child(5), 
        .cart-items-container .cart-table.table td:nth-child(5) { 
            width: 15% !important;
        }
        
        /* Reduce cart table cell heights to match web */
        .cart-table th {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.15rem !important;
            font-weight: 600;
            line-height: 1.1 !important;
        }
        
        .cart-table td {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.15rem !important;
            height: auto !important;
            vertical-align: middle !important;
            line-height: 1.1 !important;
        }
        
        /* Fix plant name column - allow text wrapping and NO max-width constraint */
        #cart-table-body .plant-name {
            max-width: none !important;
            width: auto !important;
            font-size: 0.7rem !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            overflow: visible !important;
            text-overflow: clip !important;
            line-height: 1.2 !important;
            padding: 0.25rem 0.15rem !important;
        }
        
        /* Compact quantity input */
        #cart-table-body .qty-input {
            width: 35px !important;
            min-width: 35px !important;
            height: 24px !important;
            font-size: 0.65rem !important;
            padding: 0.1rem 0.15rem !important;
        }
        
        /* Compact price and total displays */
        #cart-table-body .price-display,
        #cart-table-body .total-display {
            font-size: 0.7rem !important;
            min-width: 50px !important;
            padding: 0.25rem 0.15rem !important;
        }
        
        /* Compact delete button */
        .cart-table .remove-item-btn {
            padding: 0.15rem 0.25rem !important;
            font-size: 0.65rem !important;
        }
        
        .cart-table .remove-item-btn i {
            font-size: 0.6rem !important;
        }
        
        /* Reduce cart items container max height */
        .cart-items-container {
            max-height: 150px !important;
            padding: 0.5rem !important;
            overflow-x: auto !important; /* Allow horizontal scroll on mobile */
            -webkit-overflow-scrolling: touch !important;
        }
        
        /* Reduce Plant Selection table height to half on mobile */
        .col-lg-8 .card-body .table-responsive {
            max-height: 350px !important;
        }
        
        /* Ensure search input is fully functional on mobile */
        #plant-search {
            font-size: 16px !important; /* Prevents iOS zoom on focus */
            -webkit-appearance: none;
            appearance: none;
        }
        
        /* Ensure table rows show/hide properly on mobile */
        #plants-table-body tr {
            display: table-row !important;
        }
        
        #plants-table-body tr[style*="display: none"] {
            display: none !important;
        }
    }
    
    @media (max-width: 576px) {
        /* Even more compact on small mobile */
        .plant-selection-table {
            min-width: 650px !important;
        }
        
        .plant-selection-table th,
        .plant-selection-table td {
            padding: 0.3rem 0.2rem !important;
            font-size: 0.7rem !important;
        }
        
        .editable-field,
        .form-control-sm {
            padding: 0.1rem 0.2rem !important;
            font-size: 0.6rem !important;
            min-width: 50px !important;
            width: 60px !important;
        }
        
        .add-to-cart-btn {
            padding: 0.2rem 0.4rem !important;
            font-size: 0.65rem !important;
        }
        
        h1.mb-0 {
            font-size: 1.1rem !important;
        }
        
        .card-header h5 {
            font-size: 0.85rem !important;
        }
    }
</style>

<div class="container-fluid py-4" style="height: calc(100vh - 0px); display: flex; flex-direction: column; background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);">
    <!-- Sidebar Toggle Button for Mobile -->
    <button id="sidebarToggle" class="btn btn-success d-lg-none" type="button" aria-label="Open sidebar" style="position: fixed; top: 10px; right: 10px; z-index: 1000; width: 45px; height: 45px; border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        <i class="fa fa-bars" style="font-size: 1.3rem;"></i>
    </button>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0" style="color: #2e7d32; font-weight: 600; font-family: 'Poppins', sans-serif;">
                    <i class="fas fa-cash-register me-2" style="color: #4caf50;"></i>Point of Sale
                </h1>
                <div class="btn-group">
                    <button id="records-btn" class="btn btn-success ms-2" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); border: none; box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);">
                        <i class="fas fa-list me-1"></i> Records
                    </button>
                    <a href="<?php echo e(route('walk-in.inventory')); ?>" class="btn btn-primary ms-2" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); border: none; box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);">
                        <i class="fas fa-boxes me-1"></i> Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row" style="flex: 1; min-height: 0;">
        <div class="col-lg-8" style="display: flex; flex-direction: column;">
            <!-- Plant selection and cart area -->
            <div class="card mb-4" style="flex: 1; display: flex; flex-direction: column; min-height: 0; border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); color: white; border-radius: 12px 12px 0 0; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-leaf me-2"></i>Plant Selection
                    </h5>
                </div>
                <div class="card-body" style="flex: 1; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    <div class="row mb-3" style="flex-shrink: 0;">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="plant-search" placeholder="Search for plants...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="flex: 1; overflow-y: auto !important; display: block !important; border: 1px solid #dee2e6 !important; min-height: 0;">
                        <table class="table table-bordered plant-selection-table" style="margin-bottom: 0 !important;">
                            <thead class="table-light sticky-top" style="background-color: #fff !important; position: sticky !important; top: 0 !important; z-index: 10 !important; box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1) !important;">
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Height<br>(mm)</th>
                                    <th>Spread<br>(mm)</th>
                                    <th>Spacing<br>(mm)</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="plants-table-body">
                                <?php $__empty_1 = true; $__currentLoopData = $plants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr data-plant-id="<?php echo e($plant->id); ?>">
                                    <td><?php echo e($plant->name); ?></td>
                                    <td><?php echo e($plant->code); ?></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm editable-field" 
                                               value="<?php echo e($plant->height); ?>" min="0" style="border: 1px solid #ced4da;">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm editable-field" 
                                               value="<?php echo e($plant->spread); ?>" min="0" style="border: 1px solid #ced4da;">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm editable-field" 
                                               value="<?php echo e($plant->spacing); ?>" min="0" style="border: 1px solid #ced4da;">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm editable-field price-field" 
                                               value="<?php echo e($plant->price); ?>" min="0" step="0.01" style="border: 1px solid #ced4da;">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary add-to-cart-btn" data-plant-id="<?php echo e($plant->id); ?>" data-plant-name="<?php echo e($plant->name); ?>" data-plant-price="<?php echo e($plant->price); ?>">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No plants available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Current sale cart - Redesigned with fixed layout -->
            <div class="card mb-4 cart-card" style="border: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 12px;">
                <div class="card-header" style="background: linear-gradient(135deg, #81c784 0%, #66bb6a 100%); color: white; border-radius: 12px 12px 0 0; border: none;">
                    <h5 class="mb-0" style="font-weight: 600; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-shopping-cart me-2"></i>Current Sale
                    </h5>
                </div>
                <div class="card-body p-0 cart-body">
                    <!-- Empty cart message -->
                    <div class="text-center text-muted py-4" id="empty-cart-message">
                        <i class="fas fa-shopping-cart fa-3x mb-2"></i>
                        <p>No items in cart</p>
                    </div>
                    
                    <!-- Cart items - No scrolling, compact table -->
                    <div id="cart-items-list" class="d-none">
                        <div class="cart-items-container p-3">
                            <table class="table table-sm table-bordered cart-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Plant</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-table-body">
                                    <!-- Cart items will be added here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Subtotal -->
                        <div class="p-3 bg-light border-top border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Subtotal:</h5>
                                <h5 class="mb-0" id="cart-subtotal">₱0.00</h5>
                            </div>
                        </div>
                        
                        <!-- Customer information -->
                        <div class="p-3 customer-info">
                            <div class="mb-3">
                                <label for="receipt-number" class="form-label">Receipt Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="receipt-number" placeholder="Enter or auto-generate">
                                    <button class="btn btn-outline-secondary" type="button" id="auto-generate-receipt-btn" title="Auto Generate Receipt Number">
                                        <i class="fas fa-magic"></i> Auto
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="customer-name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer-name">
                            </div>
                            <div class="mb-3">
                                <label for="customer-email" class="form-label">Customer Email (Optional)</label>
                                <input type="email" class="form-control" id="customer-email">
                            </div>
                            <div class="mb-3">
                                <label for="payment-method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment-method">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="transfer">Bank Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fixed action buttons -->
                <div class="card-footer action-buttons" id="cart-actions">
                    <div id="empty-cart-actions">
                        <div class="d-grid">
                            <button id="new-sale-btn-empty" class="btn btn-info" style="background: linear-gradient(135deg, #4db6ac 0%, #26a69a 100%); border: none; box-shadow: 0 2px 4px rgba(38, 166, 154, 0.3); color: white;">
                                <i class="fas fa-plus me-1"></i> Start New Sale
                            </button>
                        </div>
                    </div>
                    
                    <div id="filled-cart-actions" class="d-none">
                        <div class="row g-2">
                            <div class="col-12">
                                <button id="process-sale-btn" class="btn btn-success w-100" style="background: linear-gradient(135deg, #66bb6a 0%, #4caf50 100%); border: none; box-shadow: 0 2px 4px rgba(76, 175, 80, 0.3);">
                                    <i class="fas fa-check-circle me-1"></i> Complete Sale & Print Receipt
                                </button>
                            </div>
                            <div class="col-6">
                                <button id="new-sale-btn-in-cart" class="btn btn-info w-100" style="background: linear-gradient(135deg, #4db6ac 0%, #26a69a 100%); border: none; box-shadow: 0 2px 4px rgba(38, 166, 154, 0.3); color: white;">
                                    <i class="fas fa-plus me-1"></i> New Sale
                                </button>
                            </div>
                            <div class="col-6">
                                <button id="cancel-sale-btn" class="btn btn-danger w-100" style="background: linear-gradient(135deg, #ef5350 0%, #e53935 100%); border: none; box-shadow: 0 2px 4px rgba(229, 57, 53, 0.3);">
                                    <i class="fas fa-times-circle me-1"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sales Records Modal -->
    <div class="modal fade" id="salesRecordsModal" tabindex="-1" aria-labelledby="salesRecordsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="salesRecordsModalLabel">
                        <i class="fas fa-receipt me-2"></i>Sales Records
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Quick Filter Buttons -->
                    <div class="mb-3">
                        <div class="btn-group btn-group-sm mb-2" role="group">
                            <button type="button" class="btn btn-outline-success" data-pos-filter="today" onclick="applyPOSQuickFilter('today')">
                                <i class="fas fa-calendar-day me-1"></i>Today
                            </button>
                            <button type="button" class="btn btn-outline-success" data-pos-filter="week" onclick="applyPOSQuickFilter('week')">
                                <i class="fas fa-calendar-week me-1"></i>This Week
                            </button>
                            <button type="button" class="btn btn-outline-success" data-pos-filter="month" onclick="applyPOSQuickFilter('month')">
                                <i class="fas fa-calendar-alt me-1"></i>This Month
                            </button>
                            <button type="button" class="btn btn-outline-success" data-pos-filter="year" onclick="applyPOSQuickFilter('year')">
                                <i class="fas fa-calendar me-1"></i>This Year
                            </button>
                            <button type="button" class="btn btn-outline-secondary active" data-pos-filter="all" onclick="applyPOSQuickFilter('all')">
                                <i class="fas fa-infinity me-1"></i>All Time
                            </button>
                        </div>
                    </div>
                    
                    <!-- Custom Date Range & Actions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-success text-white">From</span>
                                <input type="date" class="form-control" id="start-date">
                                <span class="input-group-text bg-success text-white">To</span>
                                <input type="date" class="form-control" id="end-date">
                                <button class="btn btn-success" id="filter-records-btn">
                                    <i class="fas fa-filter me-1"></i>Apply Custom
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-danger btn-sm" id="bulk-delete-btn" disabled>
                                    <i class="fas fa-trash me-1"></i>Delete Selected
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6;">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                                <tr>
                                    <th style="width: 40px; text-align: center;">
                                        <input type="checkbox" id="select-all-records" class="form-check-input" style="width: 18px; height: 18px; cursor: pointer;">
                                    </th>
                                    <th>Date</th>
                                    <th>Plant</th>
                                    <th>Height</th>
                                    <th>Spread</th>
                                    <th>Spacing</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Customer</th>
                                    <th>Payment Method</th>
                                </tr>
                            </thead>
                            <tbody id="sales-records-body">
                                <!-- Sales records will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="sales-records-pagination" class="d-flex justify-content-center mt-3" style="padding: 10px 0;">
                        <!-- Pagination will be added here dynamically -->
                    </div>
                    
                    <div id="no-records-message" class="text-center py-3 d-none">
                        <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
                        <p>No sales records found for the selected period.</p>
                    </div>
                    
                    <div id="records-loading" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading records...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Preview Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px; max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Sale Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="overflow-y: auto; flex: 1;">
                    <div id="receipt-content" class="bg-white" style="font-family: Arial, sans-serif; font-size: 13px; line-height: 1.4; padding: 0; margin: 0;">
                        <!-- Header -->
                        <center>
                            <div style="border-top: 2px dashed #000; border-bottom: 2px dashed #000; padding: 8px 0; margin: 8px 0;">
                                <div style="font-size: 18px; font-weight: bold; margin: 0;">SALENGA FARM</div>
                                <div style="font-size: 13px; margin: 0;">Official Sales</div>
                                <div style="font-size: 13px; margin: 0;">Receipt</div>
                            </div>
                        </center>
                        
                        <!-- Date and Receipt Info -->
                        <div style="margin: 0 0 8px 0; padding: 0 5px; font-size: 11px;">
                            <div id="receipt-date">Date: </div>
                            <div id="receipt-number-display">Receipt #: </div>
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                        
                        <!-- Customer Info -->
                        <div style="margin: 0 0 8px 0; padding: 0 5px; font-size: 11px;">
                            <div>Customer: <span id="receipt-customer-name"></span></div>
                            <div>Email: <span id="receipt-customer-email">-</span></div>
                            <div>Payment: <span id="receipt-payment-method"></span></div>
                        </div>
                        
                        <center>
                            <div style="border-top: 2px dashed #000; border-bottom: 2px dashed #000; padding: 4px 0; margin: 8px 0;">
                                <div style="font-weight: bold; font-size: 13px; margin: 0;">ITEMS</div>
                            </div>
                        </center>
                        
                        <!-- Items -->
                        <div id="receipt-items" style="margin: 0 0 8px 0; padding: 0 5px;">
                            <!-- Items will be added here -->
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                        
                        <!-- Subtotal -->
                        <div style="font-size: 14px; font-weight: bold; margin: 0 0 8px 0; padding: 0 5px;">
                            <span style="float: left;">SUBTOTAL:</span>
                            <span style="float: right;" id="receipt-subtotal">₱0.00</span>
                            <div style="clear: both;"></div>
                        </div>
                        
                        <div style="border-top: 2px dashed #000; margin: 8px 0;"></div>
                        
                        <!-- Notes -->
                        <div style="margin: 0 0 8px 0; padding: 0 5px; font-size: 11px;">
                            <div>Notes: <span id="receipt-notes">-</span></div>
                        </div>
                        
                        <!-- Footer -->
                        <center>
                            <div style="margin: 10px 0 0 0; padding: 0; font-size: 12px;">
                                <div style="font-weight: bold; margin: 0;">Thank you for</div>
                                <div style="font-weight: bold; margin: 0;">your purchase!</div>
                                <div style="margin: 4px 0 0 0;">Visit us again</div>
                            </div>
                        </center>
                        
                        <div style="border-top: 2px dashed #000; margin: 10px 0 0 0;"></div>
                    </div>
                </div>
                <div class="modal-footer" style="flex-shrink: 0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="print-receipt-btn">
                        <i class="fas fa-print me-1"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Sidebar toggle for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        if (toggle && sidebar && overlay) {
            // Toggle sidebar on button click
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            // Close sidebar when clicking overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
            
            // Close sidebar on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
            
            // Close sidebar when clicking a link (mobile only)
            if (window.innerWidth <= 991) {
                const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    });
                });
            }
        }
    });
    
    // Global function for POS quick filters (must be outside document.ready for onclick to work)
    function applyPOSQuickFilter(filter) {
        // Update button states
        $('[data-pos-filter]').removeClass('active');
        $(`[data-pos-filter="${filter}"]`).addClass('active');
        
        // Calculate date range based on filter
        const today = new Date();
        let startDate = null;
        let endDate = null;
        
        switch(filter) {
            case 'today':
                startDate = today.toISOString().split('T')[0];
                endDate = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                startDate = weekStart.toISOString().split('T')[0];
                endDate = today.toISOString().split('T')[0];
                break;
            case 'month':
                const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                startDate = monthStart.toISOString().split('T')[0];
                endDate = today.toISOString().split('T')[0];
                break;
            case 'year':
                const yearStart = new Date(today.getFullYear(), 0, 1);
                startDate = yearStart.toISOString().split('T')[0];
                endDate = today.toISOString().split('T')[0];
                break;
            case 'all':
                // No date filter
                break;
        }
        
        // Update date inputs
        $('#start-date').val(startDate || '');
        $('#end-date').val(endDate || '');
        
        // Load records (call the function from document.ready scope)
        if (typeof window.loadPOSSalesRecords === 'function') {
            window.loadPOSSalesRecords(1);
        }
    }

    $(document).ready(function() {
        let cart = [];
        let salesRecordsModal;
        let receiptModal;
        let currentPage = 1;
        
        // Initialize Bootstrap modals
        salesRecordsModal = new bootstrap.Modal(document.getElementById('salesRecordsModal'));
        receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        
        // Records button click handler
        $('#records-btn').click(function() {
            // Show modal
            salesRecordsModal.show();
            
            // Apply all time filter by default
            applyPOSQuickFilter('all');
        });
        
        // Helper function to initialize tooltips
        function initTooltips() {
            // Destroy any existing tooltips first
            $('.truncate-text[data-bs-toggle="tooltip"]').tooltip('dispose');
            
            // Initialize tooltips on all truncate-text elements
            $('.truncate-text').attr('data-bs-toggle', 'tooltip');
            
            // Initialize new tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }
        
        // Filter records button click handler
        $('#filter-records-btn').click(function() {
            currentPage = 1;
            loadSalesRecords();
        });
        
        // Function to load sales records (expose to global scope)
        window.loadPOSSalesRecords = function(page = 1) {
            loadSalesRecords(page);
        };
        
        function loadSalesRecords(page = 1) {
            currentPage = page;
            $('#no-records-message').addClass('d-none');
            $('#records-loading').removeClass('d-none');
            $('#sales-records-body').empty();
            $('#sales-records-pagination').empty();
            
            // Prepare query parameters
            const params = new URLSearchParams();
            params.append('page', page);
            
            const startDate = $('#start-date').val();
            const endDate = $('#end-date').val();
            
            if (startDate) {
                params.append('start_date', startDate);
            }
            
            if (endDate) {
                params.append('end_date', endDate);
            }
            
            // Fetch records from server
            $.ajax({
                url: '/walk-in/records?' + params.toString(),
                type: 'GET',
                success: function(response) {
                    $('#records-loading').addClass('d-none');
                    
                    if (response.success) {
                        const records = response.data.data;
                        
                        if (records.length === 0) {
                            $('#no-records-message').removeClass('d-none');
                            return;
                        }
                        
                        // Populate records table
                        records.forEach(record => {
                            const saleDate = new Date(record.sale_date);
                            const formattedDate = saleDate.toLocaleDateString() + ' ' + 
                                                saleDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            
                            $('#sales-records-body').append(`
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input record-checkbox" value="${record.id}">
                                    </td>
                                    <td>${formattedDate}</td>
                                    <td><span class="truncate-text" title="${record.plant ? record.plant.name : 'Unknown'}">${record.plant ? record.plant.name : 'Unknown'}</span></td>
                                    <td>${record.height || 'N/A'}</td>
                                    <td>${record.spread || 'N/A'}</td>
                                    <td>${record.spacing || 'N/A'}</td>
                                    <td>${record.quantity}</td>
                                    <td>₱${parseFloat(record.price).toFixed(2)}</td>
                                    <td>₱${parseFloat(record.total_price).toFixed(2)}</td>
                                    <td><span class="truncate-text" title="${record.customer_name || 'N/A'}">${record.customer_name || 'N/A'}</span></td>
                                    <td>${record.payment_method}</td>
                                </tr>
                            `);
                        });
                        
                        // Attach checkbox event handlers
                        attachCheckboxHandlers();
                        
                        // Create pagination
                        createPagination(response.data);
                        
                        // Initialize tooltips after adding content
                        initTooltips();
                    } else {
                        $('#no-records-message').removeClass('d-none')
                            .find('p').text('Error loading records: ' + response.message);
                    }
                },
                error: function(xhr) {
                    $('#records-loading').addClass('d-none');
                    $('#no-records-message').removeClass('d-none')
                        .find('p').text('Error loading records. Please try again.');
                    console.error(xhr);
                }
            });
        }
        
        // Function to create pagination links
        function createPagination(data) {
            if (data.last_page <= 1) return;
            
            const pagination = $('#sales-records-pagination');
            
            // Create pagination container
            const paginationNav = $('<nav aria-label="Sales records pagination"></nav>');
            const paginationList = $('<ul class="pagination"></ul>');
            
            // Previous page link
            const prevLi = $('<li class="page-item"></li>');
            if (data.current_page === 1) {
                prevLi.addClass('disabled');
            } else {
                prevLi.click(function() {
                    loadSalesRecords(data.current_page - 1);
                });
            }
            prevLi.append('<a class="page-link" href="javascript:void(0)">Previous</a>');
            paginationList.append(prevLi);
            
            // Page number links
            for (let i = 1; i <= data.last_page; i++) {
                const pageLi = $('<li class="page-item"></li>');
                if (i === data.current_page) {
                    pageLi.addClass('active');
                } else {
                    pageLi.click(function() {
                        loadSalesRecords(i);
                    });
                }
                pageLi.append(`<a class="page-link" href="javascript:void(0)">${i}</a>`);
                paginationList.append(pageLi);
            }
            
            // Next page link
            const nextLi = $('<li class="page-item"></li>');
            if (data.current_page === data.last_page) {
                nextLi.addClass('disabled');
            } else {
                nextLi.click(function() {
                    loadSalesRecords(data.current_page + 1);
                });
            }
            nextLi.append('<a class="page-link" href="javascript:void(0)">Next</a>');
            paginationList.append(nextLi);
            
            // Add to DOM
            paginationNav.append(paginationList);
            pagination.append(paginationNav);
        }
        
        // Handle adding items to cart
        $('.add-to-cart-btn').click(function() {
            const plantId = $(this).data('plant-id');
            const plantName = $(this).data('plant-name');
            const row = $(this).closest('tr');
            
            // Get values from editable fields
            const height = row.find('td:eq(2) input').val();
            const spread = row.find('td:eq(3) input').val();
            const spacing = row.find('td:eq(4) input').val();
            const plantPrice = parseFloat(row.find('td:eq(5) input').val());
            
            // Update data attribute with current price
            $(this).data('plant-price', plantPrice);
            
            // Check if item is already in cart
            const existingItem = cart.find(item => item.id === plantId);
            
            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.price = plantPrice; // Update price to current value
                existingItem.height = height;
                existingItem.spread = spread;
                existingItem.spacing = spacing;
                existingItem.total = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    id: plantId,
                    name: plantName,
                    price: plantPrice,
                    height: height,
                    spread: spread,
                    spacing: spacing,
                    quantity: 1,
                    total: plantPrice
                });
            }
            
            updateCartDisplay();
        });
        
        // Function to update cart display
        function updateCartDisplay() {
            const cartBody = $('#cart-table-body');
            cartBody.empty();
            
            let subtotal = 0;
            
            if (cart.length > 0) {
                $('#empty-cart-message').addClass('d-none');
                $('#cart-items-list').removeClass('d-none');
                $('#empty-cart-actions').addClass('d-none');
                $('#filled-cart-actions').removeClass('d-none');
                
                cart.forEach(item => {
                    subtotal += item.total;
                    
                    cartBody.append(`
                        <tr data-item-id="${item.id}">
                            <td class="plant-name">${item.name}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm qty-input" 
                                       value="${item.quantity}" min="1">
                            </td>
                            <td class="price-display">₱${item.price.toFixed(2)}</td>
                            <td class="total-display">₱${item.total.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
                
                $('#cart-subtotal').text(`₱${subtotal.toFixed(2)}`);
            } else {
                $('#empty-cart-message').removeClass('d-none');
                $('#cart-items-list').addClass('d-none');
                $('#empty-cart-actions').removeClass('d-none');
                $('#filled-cart-actions').addClass('d-none');
            }
            
            // Attach event handlers to new elements
            attachCartEventHandlers();
        }
        
        // Attach event handlers to cart items
        function attachCartEventHandlers() {
            // Update quantity
            $('.qty-input').change(function() {
                const row = $(this).closest('tr');
                const itemId = row.data('item-id');
                const newQuantity = parseInt($(this).val());
                
                if (newQuantity <= 0) {
                    $(this).val(1);
                    return;
                }
                
                const cartItem = cart.find(item => item.id === itemId);
                if (cartItem) {
                    cartItem.quantity = newQuantity;
                    cartItem.total = cartItem.price * newQuantity;
                    updateCartDisplay();
                }
            });
            
            // Remove item
            $('.remove-item-btn').click(function() {
                const row = $(this).closest('tr');
                const itemId = row.data('item-id');
                
                cart = cart.filter(item => item.id !== itemId);
                updateCartDisplay();
            });
        }
        
        // Search functionality
        $('#plant-search').keyup(function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('#plants-table-body tr').each(function() {
                const name = $(this).find('td:first').text().toLowerCase();
                const code = $(this).find('td:nth-child(2)').text().toLowerCase();
                
                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Function to generate receipt
        function generateReceipt(saleData) {
            console.log('=== RECEIPT DEBUG ===');
            console.log('Full saleData:', saleData);
            console.log('Receipt Number:', saleData.receipt_number);
            console.log('====================');
            
            // Set basic receipt info
            const receiptDate = new Date();
            $('#receipt-date').text('Date: ' + receiptDate.toLocaleDateString() + ' ' + receiptDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
            $('#receipt-customer-name').text(saleData.customer_name || 'Walk-in Customer');
            $('#receipt-customer-email').text(saleData.customer_email || '-');
            $('#receipt-payment-method').text(saleData.payment_method.charAt(0).toUpperCase() + saleData.payment_method.slice(1));
            $('#receipt-notes').text(saleData.notes || '-');
            
            // Use the receipt number from sale data - with fallback
            const receiptNum = saleData.receipt_number || 'NOT-PROVIDED';
            console.log('Setting receipt number to:', receiptNum);
            $('#receipt-number-display').text('Receipt #: ' + receiptNum);
            
            // Clear and populate items
            const receiptItems = $('#receipt-items');
            receiptItems.empty();
            
            let subtotal = 0;
            
            saleData.items.forEach(item => {
                subtotal += item.total;
                
                // Thermal receipt format: Item name on one line, qty x price = total on next line
                receiptItems.append(`
                    <div style="margin-bottom: 4px; border-bottom: 1px dashed #ccc; padding-bottom: 4px;">
                        <div style="font-weight: bold; font-size: 12px;">${item.name}</div>
                        <div style="font-size: 11px;">
                            <span style="float: left;">${item.quantity} x ₱${parseFloat(item.price).toFixed(2)}</span>
                            <span style="float: right; font-weight: bold;">₱${parseFloat(item.total).toFixed(2)}</span>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                `);
            });
            
            $('#receipt-subtotal').text('₱' + subtotal.toFixed(2));
            
            // Show receipt modal
            receiptModal.show();
        }
        
        // Handle print receipt button - Option A: Direct print with iframe
        $('#print-receipt-btn').click(function() {
            // Create a hidden iframe for printing
            const printFrame = document.createElement('iframe');
            printFrame.style.position = 'absolute';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = 'none';
            
            document.body.appendChild(printFrame);
            
            const receiptContent = document.getElementById('receipt-content').innerHTML;
            
            const printDocument = printFrame.contentWindow.document;
            printDocument.open();
            printDocument.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Sale Receipt</title>
                    <style>
                        /* Thermal printer optimized styles for 58mm paper on 72mm driver */
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }
                        
                        body { 
                            font-family: Arial, sans-serif;
                            padding: 0;
                            margin: 0 auto;
                            width: 58mm;
                            max-width: 58mm;
                        }
                        
                        @page {
                            size: 58mm auto;
                            margin: 0;
                        }
                    </style>
                </head>
                <body>
                    ${receiptContent}
                </body>
                </html>
            `);
            printDocument.close();
            
            // Wait for content to load, then print (increased delay for thermal printer)
            printFrame.contentWindow.focus();
            setTimeout(() => {
                printFrame.contentWindow.print();
                
                // Remove iframe after printing
                setTimeout(() => {
                    document.body.removeChild(printFrame);
                }, 1000);
            }, 1000); // Increased from 250ms to 1000ms (1 second)
        });
        
        // Process sale
        $('#process-sale-btn').click(function() {
            if (cart.length === 0) {
                AlertSystem.alert({
                    title: 'Cart Empty',
                    message: 'Please add items to cart first.',
                    type: 'warning'
                });
                return;
            }
            
            const receiptNumber = $('#receipt-number').val().trim();
            if (!receiptNumber) {
                AlertSystem.alert({
                    title: 'Receipt Number Required',
                    message: 'Please enter receipt number or click Auto to generate one.',
                    type: 'warning'
                });
                $('#receipt-number').focus();
                return;
            }
            
            const customerName = $('#customer-name').val();
            if (!customerName) {
                AlertSystem.alert({
                    title: 'Customer Name Required',
                    message: 'Please enter customer name.',
                    type: 'warning'
                });
                return;
            }
            
            // Disable button and show loading state
            const btn = $(this);
            const originalText = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
            btn.prop('disabled', true);
            
            // Prepare sale data
            const saleData = {
                receipt_number: receiptNumber,
                customer_name: customerName,
                customer_email: $('#customer-email').val(),
                payment_method: $('#payment-method').val(),
                notes: $('#notes').val(),
                items: cart.map(item => ({
                    plant_id: item.id,
                    name: item.name,
                    quantity: item.quantity,
                    price: item.price,
                    height: item.height,
                    spread: item.spread,
                    spacing: item.spacing,
                    total: item.total
                })),
                total_amount: cart.reduce((sum, item) => sum + item.total, 0)
            };
            
            // Send data to server
            $.ajax({
                url: '/walk-in/process-sale',
                type: 'POST',
                data: JSON.stringify(saleData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Reset button
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    
                    if (response.success) {
                        // Generate and show receipt
                        generateReceipt(saleData);
                        
                        // Reset cart
                        cart = [];
                        updateCartDisplay();
                        
                        // Reset form (including receipt number)
                        $('#receipt-number').val('');
                        $('#customer-name').val('');
                        $('#customer-email').val('');
                        $('#notes').val('');
                    } else {
                        AlertSystem.alert({
                            title: 'Error',
                            message: 'Error: ' + response.message,
                            type: 'error'
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    AlertSystem.alert({
                        title: 'Error',
                        message: 'Error processing sale. Please try again.',
                        type: 'error'
                    });
                    
                    // Reset button
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        });
        
        // Auto-generate receipt number button
        $('#auto-generate-receipt-btn').click(function() {
            const btn = $(this);
            const originalHtml = btn.html();
            
            // Show loading state
            btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            // Generate receipt number based on timestamp
            const timestamp = new Date().getTime();
            const receiptNumber = 'INV-' + timestamp.toString().substr(-8);
            
            // Set the receipt number
            $('#receipt-number').val(receiptNumber);
            
            // Restore button
            setTimeout(() => {
                btn.html(originalHtml).prop('disabled', false);
            }, 300);
        });
        
        // New sale button (all variants)
        $('#new-sale-btn, #new-sale-btn-empty, #new-sale-btn-in-cart').click(function() {
            // Reset the form
            cart = [];
            $('#receipt-number').val('');
            $('#customer-name').val('');
            $('#customer-email').val('');
            $('#notes').val('');
            $('#payment-method').val('cash');
            
            updateCartDisplay();
        });
        
        // Cancel sale button
        $('#cancel-sale-btn').click(function() {
            AlertSystem.confirm({
                title: 'Cancel Sale?',
                message: 'Are you sure you want to cancel this sale? All items in the cart will be removed.',
                confirmText: 'Yes, Cancel',
                cancelText: 'No, Keep Sale',
                onConfirm: function() {
                    // Reset the form
                    cart = [];
                    $('#receipt-number').val('');
                    $('#customer-name').val('');
                    $('#customer-email').val('');
                    $('#notes').val('');
                    $('#payment-method').val('cash');
                    
                    updateCartDisplay();
                    updateCartActions();
                }
            });
        });
        
        // Function to attach checkbox event handlers
        function attachCheckboxHandlers() {
            // Handle select all checkbox
            $('#select-all-records').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.record-checkbox').prop('checked', isChecked);
                toggleBulkDeleteButton();
            });
            
            // Handle individual record checkboxes
            $('.record-checkbox').off('change').on('change', function() {
                const totalCheckboxes = $('.record-checkbox').length;
                const checkedCheckboxes = $('.record-checkbox:checked').length;
                
                // Update select all checkbox state
                $('#select-all-records').prop('checked', totalCheckboxes === checkedCheckboxes);
                $('#select-all-records').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
                
                toggleBulkDeleteButton();
            });
        }
        
        // Function to enable/disable bulk delete button based on selections
        function toggleBulkDeleteButton() {
            const checkedCount = $('.record-checkbox:checked').length;
            if (checkedCount > 0) {
                $('#bulk-delete-btn').prop('disabled', false).removeClass('btn-secondary').addClass('btn-danger');
            } else {
                $('#bulk-delete-btn').prop('disabled', true).removeClass('btn-danger').addClass('btn-secondary');
            }
        }
        
        // Bulk delete functionality
        $('#bulk-delete-btn').click(function() {
            const selectedIds = [];
            $('.record-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                AlertSystem.alert({
                    title: 'No Records Selected',
                    message: 'Please select at least one record to delete.',
                    type: 'warning'
                });
                return;
            }
            
            AlertSystem.confirm({
                title: 'Delete Records?',
                message: `Are you sure you want to delete ${selectedIds.length} selected record(s)? This action cannot be undone.`,
                confirmText: 'Yes, Delete',
                cancelText: 'Cancel',
                onConfirm: function() {
                    // Disable button and show loading state
                    const btn = $('#bulk-delete-btn');
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Deleting...');
                    btn.prop('disabled', true);
                    
                    // Send delete request to server
                    $.ajax({
                        url: '/walk-in/bulk-delete',
                        type: 'DELETE',
                        data: JSON.stringify({
                            ids: selectedIds
                        }),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Reset button
                            btn.html(originalText);
                            btn.prop('disabled', false);
                            
                            if (response.success) {
                                // Show success message
                                AlertSystem.toast({
                                    message: `Successfully deleted ${selectedIds.length} record(s).`,
                                    type: 'success'
                                });
                                
                                // Reload records
                                loadSalesRecords(currentPage);
                                
                                // Reset checkboxes
                                $('#select-all-records').prop('checked', false);
                                toggleBulkDeleteButton();
                            } else {
                                AlertSystem.alert({
                                    title: 'Error',
                                    message: 'Error deleting records: ' + response.message,
                                    type: 'error'
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            AlertSystem.alert({
                                title: 'Error',
                                message: 'Error deleting records. Please try again.',
                                type: 'error'
                            });
                            
                            // Reset button
                            btn.html(originalText);
                            btn.prop('disabled', false);
                        }
                    });
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.public', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\CODING\my_Inventory\resources\views/walk-in/index.blade.php ENDPATH**/ ?>