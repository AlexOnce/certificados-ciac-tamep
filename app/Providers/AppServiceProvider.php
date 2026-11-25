<?php

namespace App\Providers;

use App\Models\AuditoriaLog;
use App\Models\Usuario;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Event::listen(Login::class, function (Login $event) {
            $user = $event->user instanceof Usuario ? $event->user : null;
            AuditoriaLog::registrar(
                evento: 'LOGIN',
                modulo: 'ACCESO',
                descripcion: 'Inicio de sesión en el sistema',
                registroId: $user?->id_usu,
                codigoReferencia: $user?->email_usu,
                usuario: $user,
            );
        });

        Event::listen(Logout::class, function (Logout $event) {
            $user = $event->user instanceof Usuario ? $event->user : null;
            if (!$user) {
                return;
            }
            AuditoriaLog::registrar(
                evento: 'LOGOUT',
                modulo: 'ACCESO',
                descripcion: 'Cierre de sesión del sistema',
                registroId: $user->id_usu,
                codigoReferencia: $user->email_usu,
                usuario: $user,
            );
        });
    }
}
