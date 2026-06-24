<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Percaya pada proxy Vercel agar HTTPS terdeteksi
        $middleware->trustProxies(at: '*');

        // Atur halaman tujuan otomatis bagi user yang sudah login (guest redirection)
        $middleware->redirectUsersTo(fn (Request $request) => 
            $request->user() && $request->user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard'
        );

        // Pasang middleware anti-back ke semua web routes
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// Vercel serverless environment is read-only, we must change the storage path to /tmp
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp');
    if (!is_dir('/tmp/framework/views')) {
        @mkdir('/tmp/framework/views', 0755, true);
        @mkdir('/tmp/framework/sessions', 0755, true);
        @mkdir('/tmp/framework/cache', 0755, true);
        @mkdir('/tmp/logs', 0755, true);
    }
}

return $app;
