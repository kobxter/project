<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // Middleware ทั่วไป เช่น TrustProxies
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware สำหรับ Web
        ],
        'api' => [
            // Middleware สำหรับ API
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Middleware อื่น ๆ ...
        'role' => \App\Http\Middleware\RoleMiddleware::class, // เพิ่ม role middleware
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    ];
}
