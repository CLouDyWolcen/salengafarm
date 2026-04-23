<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientRequestController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalkInSalesController;
use App\Http\Controllers\UserPlantRequestController;
use App\Http\Controllers\WalkInInventoryController;
use App\Http\Controllers\SiteVisitController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\SystemLogController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


// Test email routes (remove after testing)
Route::get('/test-brevo-api', function () {
    try {
        $brevoService = new \App\Services\BrevoEmailService();
        $result = $brevoService->sendEmail(
            'farmsalenga@gmail.com',
            'Test Email via Brevo API - ' . now(),
            '<h1>Test Email</h1><p>This email was sent via Brevo API at ' . now() . '</p>'
        );
        
        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::get('/test-email-simple', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email from Salenga Farm - ' . now(), function($message) {
            $message->to('farmsalenga@gmail.com')
                    ->subject('Test Email - ' . now());
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Email sent! Check farmsalenga@gmail.com',
            'time' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'error_class' => get_class($e),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ], 500);
    }
});

Route::get('/test-email-config', function () {
    $config = [
        'MAIL_MAILER' => env('MAIL_MAILER'),
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_USERNAME' => env('MAIL_USERNAME'),
        'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
        'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
        'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
        'MAIL_PASSWORD_SET' => !empty(env('MAIL_PASSWORD')) ? 'YES' : 'NO',
        'RESEND_KEY_SET' => !empty(env('RESEND_KEY')) ? 'YES (PROBLEM!)' : 'NO (Good)',
        'config_mail_default' => config('mail.default'),
        'config_mail_host' => config('mail.mailers.smtp.host'),
        'config_mail_port' => config('mail.mailers.smtp.port'),
    ];
    
    return response()->json($config, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/test-send-email/{email}', function ($email) {
    try {
        Log::info('Test email attempt', [
            'to' => $email,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
        ]);
        
        Mail::raw('Test email from Salenga Farm via Brevo SMTP. If you receive this, the configuration is working!', function ($message) use ($email) {
            $message->to($email)
                    ->subject('Test Email - Brevo Configuration Check');
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Email sent! Check your inbox and Brevo dashboard.',
            'sent_to' => $email
        ]);
    } catch (\Exception $e) {
        Log::error('Test email failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Public routes
Route::get('/', [PublicController::class, 'index'])->name('public.plants');

// Plant Care Library - accessible to authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/plant-care', [App\Http\Controllers\PlantCareController::class, 'index'])->name('plant-care.index');
    Route::get('/plant-care/{id}', [App\Http\Controllers\PlantCareController::class, 'show'])->name('plant-care.show');
});

// Plant Care Edit - Admin only
Route::middleware(['auth'])->group(function () {
    // Admin Plant Care Management Page
    Route::get('/admin/plant-care', [App\Http\Controllers\PlantCareController::class, 'adminIndex'])->name('plant-care.admin');
    
    Route::get('/plant-care/{id}/edit', [App\Http\Controllers\PlantCareController::class, 'edit'])->name('plant-care.edit');
    Route::put('/plant-care/{id}', [App\Http\Controllers\PlantCareController::class, 'update'])->name('plant-care.update');
});

Route::post('/display-plants', [PublicController::class, 'store'])->name('display-plants.store');
Route::put('/display-plants/{plant}', [PublicController::class, 'update'])->name('display-plants.update');
Route::delete('/display-plants/{plant}', [PublicController::class, 'destroy'])->name('display-plants.destroy');
Route::post('/display-plants/photo/upload', [PublicController::class, 'uploadPhoto'])->name('display-plants.upload.photo');
Route::delete('/display-plants/photo/remove/{plant}', [PublicController::class, 'removePhoto'])->name('display-plants.remove.photo');

// Client-only plant request routes
Route::middleware(['auth', 'can:client-access'])->prefix('user/plant-request')->name('user.plant-request.')->group(function () {
    Route::get('/', [UserPlantRequestController::class, 'create'])->name('create');
    Route::get('/select-plants', [UserPlantRequestController::class, 'selectPlants'])->name('select-plants');
    Route::post('/store', [UserPlantRequestController::class, 'store'])->name('store');
    Route::get('/success/{id}', [UserPlantRequestController::class, 'success'])->name('success');
    Route::get('/download-pdf/{id}', [UserPlantRequestController::class, 'downloadPdf'])->name('download-pdf');
});

// Client RFQ routes - for clients only
Route::middleware(['auth', 'can:client-access'])->group(function () {
    Route::post('/client-request', [ClientRequestController::class, 'store'])->name('client-request.store');
    Route::get('/request-success/{id}', [ClientRequestController::class, 'showSuccess'])->name('request-success');
});

// For public users without authentication
Route::post('/client-request-public', [ClientRequestController::class, 'store'])->name('client-request.public');
Route::get('/request-success-public/{id}', [ClientRequestController::class, 'showSuccess'])->name('request-success-public');

// Social Login Routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');

// Authentication routes
Route::middleware(['auth'])->group(function () {
    // Regular user routes
    Route::get('/home', [PublicController::class, 'index'])->name('home');

    // User/Client Dashboard (Request Center)
    Route::get('/dashboard/user', [UserDashboardController::class, 'index'])->name('dashboard.user');
    
    // User inquiry response view
    Route::get('/user/inquiries/{id}/response', [UserDashboardController::class, 'viewResponse'])->name('user.inquiry.response');
    
    // Client request submission
    Route::post('/client-request/submit', [UserDashboardController::class, 'submitClientRequest'])->name('client-request.submit');
    
    // User can delete their own requests
    Route::delete('/user/requests/{id}', [ClientRequestController::class, 'destroy'])->name('user.requests.destroy');

    // Notification routes (for all authenticated users)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('delete-all');
        Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    });

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Role request routes
        Route::get('/users/role-requests/{id}/edit', [UserController::class, 'editRoleRequest'])->name('users.role-requests.edit');
        Route::put('/users/role-requests/{id}', [UserController::class, 'updateRoleRequest'])->name('users.role-requests.update');
        Route::delete('/users/role-requests/{id}', [UserController::class, 'deleteRoleRequest'])->name('users.role-requests.delete');
        Route::post('/users/role-requests/{id}/approve', [UserController::class, 'approveRoleRequest'])->name('users.role-requests.approve');
        Route::post('/users/role-requests/{id}/reject', [UserController::class, 'rejectRoleRequest'])->name('users.role-requests.reject');
        
        // System Logs (Super Admin only) - API endpoint
        Route::get('/admin/logs/fetch', [SystemLogController::class, 'fetchLogs'])->name('admin.logs.fetch');
        Route::post('/admin/logs/clear', [SystemLogController::class, 'clear'])->name('admin.logs.clear');
        Route::get('/admin/logs/download', [SystemLogController::class, 'download'])->name('admin.logs.download');
    });

    // Admin and Manager routes
    Route::middleware(['can:access-admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Add plant search API endpoint before the resource route
        Route::get('/plants/search', [PlantController::class, 'search'])->name('plants.search');

        Route::resource('plants', PlantController::class)->except(['create', 'edit', 'show']);
        Route::post('/plants/bulk-update', [PlantController::class, 'bulkUpdate'])->name('plants.bulk-update');
        Route::post('/update-stock', [DashboardController::class, 'updateStock'])->name('update-stock');
        
        // Analytics API endpoint
        Route::get('/dashboard/analytics', [DashboardController::class, 'getAnalyticsData'])->name('dashboard.analytics');

        // Categories (persisted)
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Walk-in sales routes
        Route::get('/walk-in', [WalkInSalesController::class, 'index'])->name('walk-in.index');
        Route::get('/walk-in/sales-records', [WalkInSalesController::class, 'showRecords'])->name('walk-in.sales-records');
        Route::post('/walk-in/process-sale', [WalkInSalesController::class, 'processSale'])->name('walk-in.process-sale');
        Route::get('/walk-in/records', [WalkInSalesController::class, 'getSalesRecords'])->name('walk-in.records');
        Route::get('/walk-in/percentages', [WalkInSalesController::class, 'getSalesPercentages'])->name('walk-in.percentages');
        Route::delete('/walk-in/bulk-delete', [WalkInSalesController::class, 'bulkDelete'])->name('walk-in.bulk-delete');

        // Walk-in inventory management routes
        Route::get('/walk-in/inventory', [WalkInInventoryController::class, 'index'])->name('walk-in.inventory');
        Route::post('/walk-in/inventory/update', [WalkInInventoryController::class, 'updateInventory'])->name('walk-in.inventory.update');
        Route::get('/walk-in/inventory/stats', [WalkInInventoryController::class, 'getInventoryStats'])->name('walk-in.inventory.stats');
        Route::get('/walk-in/inventory/summary', [WalkInInventoryController::class, 'getSummary'])->name('walk-in.inventory.summary');

        // Requests management routes
        Route::get('/requests', [ClientRequestController::class, 'index'])->name('requests.index');
        Route::post('/requests/send-email/{id}', [ClientRequestController::class, 'sendEmail'])->name('requests.send-email');
        Route::post('/requests/{id}/send-response', [ClientRequestController::class, 'sendResponse'])->name('requests.send-response');
        Route::get('/requests/view/{id}', [ClientRequestController::class, 'plainViewRequest'])->name('requests.view');
        Route::delete('/requests/{id}', [ClientRequestController::class, 'destroy'])->name('requests.destroy');
        Route::post('/requests/update/{id}', [ClientRequestController::class, 'updateRequest'])->name('requests.update');
        Route::post('/requests/update-pricing/{id}', [ClientRequestController::class, 'updatePricing'])->name('requests.update-pricing');
        Route::post('/requests/update-info/{id}', [ClientRequestController::class, 'updateInfo'])->name('requests.update-info');
        Route::post('/requests/update-client/{id}', [ClientRequestController::class, 'updateClient'])->name('requests.update-client');
        Route::post('/requests/update-items/{id}', [ClientRequestController::class, 'updateItems'])->name('requests.update-items');
        Route::get('/requests/direct-view/{id}', [ClientRequestController::class, 'plainViewRequest'])->name('requests.direct-view');

        // Site Visits routes
        Route::get('/site-visits', [SiteVisitController::class, 'index'])->name('site-visits.index');
        Route::resource('site-visits', SiteVisitController::class)->except(['index']);
        // Quick status update from show page
        Route::post('/site-visits/{siteVisit}/status', [SiteVisitController::class, 'updateStatus'])->name('site-visits.update-status');
        Route::get('/site-visits-data', [SiteVisitController::class, 'getVisitsJson'])->name('site-visits.data');
        Route::get('/site-visits/{siteVisit}/data', [SiteVisitController::class, 'getVisitData'])->name('site-visits.get-data');
        Route::delete('/site-visits/{siteVisit}/media/{file_index}', [SiteVisitController::class, 'deleteMediaFile'])->name('site-visits.delete-media');

        // Site Visit Checklists endpoints moved to general auth below

        // Move these routes inside the admin middleware group
        Route::post('/plants/photo/upload', [PlantController::class, 'uploadPhoto']);
        Route::delete('/plants/photo/remove/{plant}', [PlantController::class, 'removePhoto']);
    });

    // Common routes for all authenticated users
    
    // PDF routes - accessible to all authenticated users for their own requests
    Route::get('/requests/download-pdf/{id}', [ClientRequestController::class, 'downloadPdf'])->name('requests.download-pdf');
    Route::get('/requests/view-pdf/{id}', [ClientRequestController::class, 'viewPdf'])->name('requests.view-pdf');
    
    // Site Visit Checklists: allow client to upload Client's Data and approve Proposal; admin checks enforced in controller
    Route::post('/site-visits/{siteVisit}/client-data/{itemKey}/upload', [SiteVisitController::class, 'uploadClientData'])
        ->name('site-visits.client-data.upload');
    Route::delete('/site-visits/{siteVisit}/client-data/{itemKey}/{fileIndex}', [SiteVisitController::class, 'deleteClientData'])
        ->name('site-visits.client-data.delete');
    Route::delete('/site-visits/{siteVisit}/client-data-bulk-delete', [SiteVisitController::class, 'bulkDeleteClientData'])
        ->name('site-visits.client-data.bulk-delete');
    Route::post('/site-visits/{siteVisit}/client-data/{itemKey}/status', [SiteVisitController::class, 'setClientDataItemStatus'])
        ->name('site-visits.client-data.status');
    Route::post('/site-visits/{siteVisit}/proposal/{itemKey}/upload', [SiteVisitController::class, 'uploadProposalItem'])
        ->name('site-visits.proposal.upload');
    Route::post('/site-visits/{siteVisit}/proposal/approval', [SiteVisitController::class, 'setProposalApproval'])
        ->name('site-visits.proposal.approval');

    // Client: My Site Visits
    Route::get('/my-site-visits', [SiteVisitController::class, 'myVisits'])->name('site-visits.my');
    // Client-accessible Site Visit details (read-only except uploads), gated in controller
    Route::get('/site-visits/{siteVisit}/view', [SiteVisitController::class, 'showForClient'])->name('site-visits.view');
    // Client Data (client-only focused pages)
    Route::get('/client-data', [SiteVisitController::class, 'clientDataIndex'])->name('client-data.index');
    Route::get('/client-data/{siteVisit}', [SiteVisitController::class, 'clientDataShow'])->name('client-data.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // Request Form route for authenticated users
    Route::get('/request-form', [App\Http\Controllers\RequestFormController::class, 'index'])->name('request-form');
    Route::post('/request-form', [App\Http\Controllers\RequestFormController::class, 'store'])->name('request-form.store');
    Route::get('/request-form/confirmation', [App\Http\Controllers\RequestFormController::class, 'confirmation'])->name('request-form.confirmation');


});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});

Route::get('/client-requests', [DashboardController::class, 'clientRequests'])->name('client.requests');

require __DIR__ . '/auth.php';

