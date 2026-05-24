Internal Server Error

Error
Call to a member function getProfileCompletionPercentage() on null
GET 127.0.0.1:8000
PHP 8.2.12 — Laravel 11.36.1

Expand
vendor frames

C:\CODING\my_Inventory\resources\views\public\plants.blade.php
:3005
require

Illuminate\Filesystem\Filesystem
:123
Illuminate\Filesystem\{closure}

Illuminate\Filesystem\Filesystem
:124
getRequire

Illuminate\View\Engines\PhpEngine
:58
evaluatePath

Illuminate\View\Engines\CompilerEngine
:75
get

Illuminate\View\View
:209
getContents

Illuminate\View\View
:192
renderContents

Illuminate\View\View
:161
render

Illuminate\Http\Response
:70
setContent

Illuminate\Http\Response
:35
__construct

Illuminate\Routing\Router
:920
toResponse

Illuminate\Routing\Router
:887
prepareResponse

Illuminate\Routing\Router
:807
Illuminate\Routing\{closure}

Illuminate\Pipeline\Pipeline
:144
Illuminate\Pipeline\{closure}

Illuminate\Routing\Middleware\SubstituteBindings
:51
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
C:\CODING\my_Inventory\resources\views\public\plants.blade.php :3005
                                padding: 1rem; 
                                margin: 0.75rem 0;
                                border-left: 4px solid #ffc107;">
                        <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">Profile Completion</div>
                        <div style="font-size: 1.75rem; font-weight: bold; color: #28a745; margin-bottom: 0.5rem;">
                            {{ auth()->user()->getProfileCompletionPercentage() }}%
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #e9ecef;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ auth()->user()->getProfileCompletionPercentage() }}%; border-radius: 10px;"
                                 aria-valuenow="{{ auth()->user()->getProfileCompletionPercentage() }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
 
Request
GET /
Headers
host
127.0.0.1:8000
connection
keep-alive
sec-ch-ua
"Chromium";v="148", "Google Chrome";v="148", "Not/A)Brand";v="99"
sec-ch-ua-mobile
?0
sec-ch-ua-platform
"Windows"
upgrade-insecure-requests
1
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36
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
http://127.0.0.1:8000/my-requests
accept-encoding
gzip, deflate, br, zstd
accept-language
en-US,en;q=0.9
cookie
XSRF-TOKEN=eyJpdiI6IkpUZXdrMnJWNTFoOE1DUWhXLzIweHc9PSIsInZhbHVlIjoibWFvNndGZTJSWFMwdHJ0WTlkSElkR216Mnp4SEFSdEd0eTRUY2hXS1RYSW4wNmdqcjRlNHRmSnU0K3VWVWZDaXFGaW94eVEyRGlLcnpuRkMrWUlodDRWUHNSMGpibnpmYll5V0lzd25sMDFZYXZ6bm1VbUlkeWhLYjlJemI3M0giLCJtYWMiOiIzMzQ3MmFjYWFjNDQ0MmQ2YTZjNGZkNGQ2MmFkOTA1ZmVlNDMxZmI4NjgwNTU4MjI2MzExNDZhZmViYjJkODdmIiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6ImVNMjYwWXJ2d1VQUXlWNzUrMEIzK2c9PSIsInZhbHVlIjoiY1ZCNUFhdnhIV3JPNUsyY293RGJGNlY0Wkl0SHM5ckxjeHJ0NUVXRUFQUDJrMHV6MzFjUHo3VzJ1c2tWNEVHK05vMEc1b25aQUNQV0d5SGd1dzhsejJ4b3BHandJYXZPVGphcEhlYTlUUE02OGtQN3J2cE9mbzhWUGZqSlYxUEgiLCJtYWMiOiI3N2ZlNDAyMzY3NGE0YTE2NGYyNmE1N2ViNzE1YThlNTI4M2RhMGUwYjE3ZjQ1YTI5Y2VjZTU2NjZmNzliZjIyIiwidGFnIjoiIn0%3D
Body
No body data
Application
Routing
controller
App\Http\Controllers\PublicController@index
route name
public.plants
middleware
web
Database Queries
mysql (712.48 ms)
select * from `display_plants`
mysql (42.32 ms)
select * from `plants` order by `name` asc
mysql (33.13 ms)
select * from `categories` where `name` not in ('shrub', 'herbs', 'palm', 'tree', 'grass', 'bamboo', 'fertilizer')