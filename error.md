Internal Server Error

Error
Class "App\Http\Controllers\Notification" not found
PATCH 127.0.0.1:8000
PHP 8.2.12 — Laravel 11.36.1

Expand
vendor frames

App\Http\Controllers\SiteVisitController
:1191
approveRequest

Illuminate\Routing\ControllerDispatcher
:47
dispatch

Illuminate\Routing\Route
:266
runController

Illuminate\Routing\Route
:212
run

Illuminate\Routing\Router
:808
Illuminate\Routing\{closure}

Illuminate\Pipeline\Pipeline
:144
Illuminate\Pipeline\{closure}

Illuminate\Auth\Middleware\Authorize
:60
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Routing\Middleware\SubstituteBindings
:51
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Auth\Middleware\Authenticate
:64
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
:88
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\View\Middleware\ShareErrorsFromSession
:49
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Session\Middleware\StartSession
:121
handleStatefulRequest

Illuminate\Session\Middleware\StartSession
:64
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse
:37
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Cookie\Middleware\EncryptCookies
:75
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Pipeline\Pipeline
:119
then

Illuminate\Routing\Router
:807
runRouteWithinStack

Illuminate\Routing\Router
:786
runRoute

Illuminate\Routing\Router
:750
dispatchToRoute

Illuminate\Routing\Router
:739
dispatch

Illuminate\Foundation\Http\Kernel
:201
Illuminate\Foundation\Http\{closure}

Illuminate\Pipeline\Pipeline
:144
Illuminate\Pipeline\{closure}

Illuminate\Foundation\Http\Middleware\TransformsRequest
:21
handle

Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull
:31
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Foundation\Http\Middleware\TransformsRequest
:21
handle

Illuminate\Foundation\Http\Middleware\TrimStrings
:51
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Http\Middleware\ValidatePostSize
:27
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance
:110
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Http\Middleware\HandleCors
:49
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Http\Middleware\TrustProxies
:58
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks
:22
handle

Illuminate\Pipeline\Pipeline
:183
Illuminate\Pipeline\{closure}

Illuminate\Pipeline\Pipeline
:119
then

Illuminate\Foundation\Http\Kernel
:176
sendRequestThroughRouter

Illuminate\Foundation\Http\Kernel
:145
handle

Illuminate\Foundation\Application
:1190
handleRequest

C:\CODING\my_Inventory\public\index.php
:17
require_once

C:\CODING\my_Inventory\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
:23
C:\CODING\my_Inventory\app\Http\Controllers\SiteVisitController.php :1191
            'site_visit_id' => $siteVisit->id,
        ]);
 
        // Create notification for the client
        if ($siteVisitRequest->user_id) {
            Notification::create([
                'user_id' => $siteVisitRequest->user_id,
                'type' => 'site_visit_approved',
                'title' => 'Site Visit Request Approved',
                'message' => 'Your site visit request has been approved! You can now view the site data and upload your documents.',
                'link' => route('client-data.show', $siteVisit->id),
                'is_read' => false,
            ]);
        }
 
        return redirect()->route('site-visit-requests.index')
            ->with('success', 'Site visit request approved and site visit created successfully. The site visit is now in the Pending tab.');
Request
PATCH /site-visit-requests/4/approve
Headers
host
127.0.0.1:8000
connection
keep-alive
content-length
74
cache-control
max-age=0
sec-ch-ua
"Chromium";v="148", "Google Chrome";v="148", "Not/A)Brand";v="99"
sec-ch-ua-mobile
?0
sec-ch-ua-platform
"Windows"
upgrade-insecure-requests
1
content-type
application/x-www-form-urlencoded
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36
origin
http://127.0.0.1:8000
accept
text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
sec-fetch-site
same-origin
sec-fetch-mode
navigate
sec-fetch-user
?1
sec-fetch-dest
document
referer
http://127.0.0.1:8000/site-visit-requests
accept-encoding
gzip, deflate, br, zstd
accept-language
en-US,en;q=0.9
cookie
XSRF-TOKEN=eyJpdiI6Im52eHEwRExkcGJXY1Z1SGJ0LytBSFE9PSIsInZhbHVlIjoiZ0ZZQ2V5YmdidTZUSWp4UEQxYXkxelVTMnUzSk9kbDFlTWxVUjE1WXpMM21LdGxaMzdpdklGNzk5ZXJDVTdweTh1SE1BVVpGeGJ1YzdiUE9SUVdEMU80bTZKaDRoK25GcnRlTmRqWXZjNUEwR0dwQjZWUjIzUm11cU5KZFlEdHMiLCJtYWMiOiI2YjZkNDI4ODg1MmNmZTU4YTBhMzNjNDZhYjE3ZWU3ZDJhYjJiMzhhNGMwNGE5MGIyOTVmMTY1MThjYTdiNGRjIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6Ilh2anVwd3BSVXVtaFRIMi9JVllXQVE9PSIsInZhbHVlIjoiVHZrTm5TSnNwUHlrYmdJWDU4Qy9KY1gzQjR3N2F5MGRjanp1RkpyTXVrM3hCSEQySnRhWUtNRUFjczFQem1NM090L2dSOGsrZ05kUVNlT0QzQldUenhEclUxVUh4UEV2dUtDTzRPdnVDQW9rNDZtckJzNXRtc1RPUnJjcWQrVDMiLCJtYWMiOiJlMTkwZDYwNGZkMGNjZmI3NzE2ODhmOWY5MzAwNzRlNDkyNGM4NzAyYjNlM2U2ZjI3YmM3ZjBhYzA3MDkyNzZiIiwidGFnIjoiIn0%3D
Body
{
    "_token": "rWHJTpc81BXQjOsThmXAetlx7vmNpeCGigfxsTLZ",
    "_method": "PATCH",
    "admin_notes": null
}
Application
Routing
controller
App\Http\Controllers\SiteVisitController@approveRequest
route name
site-visit-requests.approve
middleware
web, auth, can:access-admin
Routing Parameters
{
    "siteVisitRequest": {
        "id": 4,
        "user_id": 4,
        "site_visit_id": 17,
        "preferred_date": "2026-05-16T00:00:00.000000Z",
        "preferred_time": "00:23:00",
        "property_address": "sadf",
        "property_size": "asdf",
        "current_condition": "other",
        "project_description": "fasdf",
        "special_requirements": "asdf",
        "photos": [],
        "status": "approved",
        "admin_notes": null,
        "rejection_reason": null,
        "reviewed_by": 2,
        "reviewed_at": "2026-05-12T18:56:15.000000Z",
        "created_at": "2026-05-12T18:53:56.000000Z",
        "updated_at": "2026-05-12T18:56:16.000000Z"
    }
}
Database Queries
mysql (4.6 ms)
select * from `users` where `id` = 2 limit 1
mysql (1.38 ms)
select * from `site_visit_requests` where `id` = '4' limit 1
mysql (25.62 ms)
update `site_visit_requests` set `status` = 'approved', `reviewed_by` = 2, `reviewed_at` = '2026-05-12 18:56:15', `site_visit_requests`.`updated_at` = '2026-05-12 18:56:15' where `id` = 4
mysql (1.17 ms)
select * from `users` where `users`.`id` = 4 limit 1
mysql (22.94 ms)
insert into `site_visits` (`user_id`, `location_address`, `client`, `contact_number`, `email`, `visit_date`, `status`, `client_data_open`, `notes`, `updated_at`, `created_at`) values (4, 'sadf', 'Clement Teneciel', '09667590644', 'client1@salenga.com', '2026-05-16 00:00:00', 'pending', '1', 'Created from Site Visit Request #4

Project Description: fasdf
Property Size: asdf
Current Condition: other
Special Requirements: asdf', '2026-05-12 18:56:15', '2026-05-12 18:56:15')
mysql (27.72 ms)
update `site_visit_requests` set `site_visit_id` = 17, `site_visit_requests`.`updated_at` = '2026-05-12 18:56:16' where `id` = 4