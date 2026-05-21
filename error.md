Internal Server Error

ErrorException
Undefined array key "description"
GET esthersflowergarden.local:8000
PHP 8.2.12 — Laravel 11.36.1

Expand
vendor frames

C:\CODING\my_Inventory\resources\views\public\plants.blade.php
:1683
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
C:\CODING\my_Inventory\resources\views\public\plants.blade.php :1683
        <div class="splash-content">
            @if(\App\Helpers\BrandHelper::getSplashType() === 'about')
                {{-- Esther's Garden - About Us --}}
                @php $about = \App\Helpers\BrandHelper::getAboutContent(); @endphp
                <h1 class="display-4">{{ $about['title'] }}</h1>
                <p class="lead mb-4">{{ $about['description'] }}</p>
                <div class="about-features mb-4">
                    <ul class="list-unstyled">
                        @foreach($about['features'] as $feature)
                            <li><i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                {{-- Salenga Farm - Welcome with Info --}}
                @php $welcome = \App\Helpers\BrandHelper::getWelcomeContent(); @endphp
                <h1 class="display-4">{{ $welcome['title'] }}</h1>
Request
GET /
Headers
host
esthersflowergarden.local:8000
connection
keep-alive
cache-control
max-age=0
upgrade-insecure-requests
1
user-agent
Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36
accept
text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7
accept-encoding
gzip, deflate
accept-language
en-US,en;q=0.9
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
mysql (18.89 ms)
select * from `display_plants`
mysql (2.73 ms)
select * from `plants` order by `name` asc
mysql (1.26 ms)
select * from `categories` where `name` not in ('shrub', 'herbs', 'palm', 'tree', 'grass', 'bamboo', 'fertilizer')