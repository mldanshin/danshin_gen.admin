<?php

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Exceptions\PageNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (ApiException $e) {
            Log::alert($e->getMessage());
            abort($e->status);
        });
        $exceptions->report(function (PageNotFoundException $e) {
            abort(404);
        });
        $exceptions->report(function (BadRequestException $e) {
            Log::alert($e->getMessage());
            abort(422);
        });
    })->create();
