Internal Server Error

ParseError
syntax error, unexpected token "return", expecting "function" or "const"
GET 127.0.0.1:8000
PHP 8.2.12 — Laravel 11.36.1

Expand
vendor frames

App\Http\Controllers\ClientRequestController
:550
Composer\Autoload\{closure}

C:\CODING\my_Inventory\vendor\composer\ClassLoader.php
:427
is_a

Illuminate\Routing\Route
:1117
controllerMiddleware

Illuminate\Routing\Route
:1054
gatherMiddleware

Illuminate\Routing\Router
:820
gatherRouteMiddleware

Illuminate\Routing\Router
:802
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
C:\CODING\my_Inventory\app\Http\Controllers\ClientRequestController.php :550
            
            return redirect()->back()->with('error', 'Failed to view PDF. Please try again.');
        }
    }
            
            return redirect()->back()->with('error', 'Failed to download PDF. Please try again.');
        }
    }
    
    public function viewPdf($id)
    {
        try {
            $request = PlantRequest::findOrFail($id);
            
            // Authorization: Allow if user is admin/manager/super_admin OR if it's their own request
            $user = auth()->user();
            $isAdminOrManager = in_array($user->role, ['admin', 'manager', 'super_admin']);
Request
GET /requests
Headers
host
127.0.0.1:8000
connection
keep-alive
sec-ch-ua
"Google Chrome";v="149", "Chromium";v="149", "Not)A;Brand";v="24"
sec-ch-ua-mobile
?0
sec-ch-ua-platform
"Windows"
upgrade-insecure-requests
1
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36
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
http://127.0.0.1:8000/dashboard
accept-encoding
gzip, deflate, br, zstd
accept-language
en-US,en;q=0.9
cookie
XSRF-TOKEN=eyJpdiI6InhvdUhMV2VxMXp1dnkrWHFVa2l4eEE9PSIsInZhbHVlIjoiU3dIcXhSZXh5L0RpS1E1QlQ1eDhjKzBXTlQ1SWRsS25jRThqU05OUWdYREpvbkJMd0JhL0dXbUt6ZXg2WnVOaXJuMDZrOE1FR3RTc2ptckVibml4eHpYRjNpUU00S051Z08ySVVzUnZYbGV0UUhBdmVmRUNncklubmU0NzU3clUiLCJtYWMiOiJlOWRhNTVmYjg2ZjM4YmZkZGVlZmMzMjM5NGJmZjNkODMyYjFjODA1OWE1MzNiZWMyZDRjYjE0MzAwYmZiMmExIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6Ijhua3VkRW5CVlU1OHBFb0F0blNpUUE9PSIsInZhbHVlIjoiRmlGZE8va2psVWFtZTQ4VTg0ekhzWFpMZEVlTUZxUmNRbU5xV2o3dTg4ZjNuZmEvMWNEZDRmNXB6N3c4SS90T3MwYUlaSmRUNFBlTnhSeUZqOFEvQ3BHTlpGVjlKVWcwZ25Vd0VnOE5YKys1RjRMYmJvRDE4eFQ2azlJclhZbjEiLCJtYWMiOiI1YmEwMTMyZDEzZjAyMjc0MjhmNzQxMTRjOTgxN2ZlMTgyZjI1OTEwNjI0ODlkZDVjYmI2ODUwMjA5MWZhZTc4IiwidGFnIjoiIn0%3D
Body
No body data
Application
Routing
controller
App\Http\Controllers\ClientRequestController@index
route name
requests.index
Database Queries
No query data