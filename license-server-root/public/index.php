<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Installed
|--------------------------------------------------------------------------
|
| If the application is not installed yet, handle installation
|
*/

if (!file_exists(__DIR__.'/../storage/installed')) {
    // Handle install route directly
    if ($_SERVER['REQUEST_URI'] === '/install' ||
        strpos($_SERVER['REQUEST_URI'], '/install') === 0 ||
        !file_exists(__DIR__.'/../.env')) {

        // Create basic .env if it doesn't exist
        if (!file_exists(__DIR__.'/../.env') && file_exists(__DIR__.'/../.env.example')) {
            copy(__DIR__.'/../.env.example', __DIR__.'/../.env');
        }

        require __DIR__.'/../install.php';
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

$response->send();

$kernel->terminate($request, $response);