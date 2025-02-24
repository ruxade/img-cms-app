<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Create and configure the application instance.
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',       // Load web routes
        commands: __DIR__.'/../routes/console.php', // Load console commands
        health: '/up',                            // Health check route
    )
    ->withMiddleware(function (Middleware $middleware) {
      // Register global middleware here if needed.
      // You can also register Sanctum-specific middleware aliases here.
      $middleware->alias([
          // These middleware help you check for token abilities.
          'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
          'ability'  => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
      ]);
  })
    ->withExceptions(function (Exceptions $exceptions) {
        // Register exception handling.
    })->create();


    Facade::setFacadeApplication($app);

// Eloquent ORM
// $app->bootEloquent();

// Register the 'auth' alias to your custom Authenticate middleware.
$app->router->aliasMiddleware('auth', \App\Http\Middleware\Authenticate::class);



// Manually load API routes with a '/api' prefix and the default controllers namespace.
$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'prefix'    => 'api',
], function ($router) {
    require __DIR__.'/../routes/api.php';
});

return $app;
