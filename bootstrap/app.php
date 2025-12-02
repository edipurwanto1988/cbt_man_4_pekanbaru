<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.role' => \App\Http\Middleware\AdminRoleMiddleware::class,
        ]);
        
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('admin/login') || $request->routeIs('admin.login')) {
                return null;
            }
            
            if ($request->is('participant/login') || $request->routeIs('participant.login')) {
                return null;
            }
            
            if ($request->is('participant/*') || $request->routeIs('participant.*')) {
                return '/participant/login';
            }
            
            return '/admin/login';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin/*') || $request->routeIs('admin.*')) {
                return redirect('/admin/login');
            }
            
            if ($request->is('participant/*') || $request->routeIs('participant.*')) {
                return redirect('/participant/login');
            }
        });
    })->create();
