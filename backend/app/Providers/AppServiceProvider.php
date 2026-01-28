<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- 1. AÑADE ESTO

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. AÑADE ESTO DENTRO DE BOOT
        Paginator::useBootstrapFive(); 
        // O si prefieres el estilo simple de Tailwind y estás seguro de tenerlo:
        // Paginator::defaultView('pagination::tailwind');
    }
}