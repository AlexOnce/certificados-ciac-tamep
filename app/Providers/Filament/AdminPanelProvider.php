<?php

namespace App\Providers\Filament;

// --- ASEGÚRATE DE TENER ESTAS IMPORTACIONES ARRIBA ---
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Auth\Login;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('14rem')
            ->sidebarCollapsibleOnDesktop()
            ->brandLogo(fn() => view('filament.admin.logo'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/escudo-ciac.png') . '?v=2')
            ->colors([
                'primary' => Color::hex('#19499C'), // Azul Principal TAM
                'warning' => Color::hex('#FFCD05'), // Amarillo TAM

                // Mapeamos los grises del sistema para que coincidan con el Login (#090d16)
                'gray' => [
                    50 => '#f8fafc',
                    100 => '#f1f5f9',
                    200 => '#e2e8f0',
                    300 => '#cbd5e1',
                    400 => '#94a3b8',
                    500 => '#64748b',
                    600 => '#475569',
                    700 => '#334155',
                    800 => '#1e293b',
                    900 => '#111827', // Modo Oscuro: Fondo de Tarjetas, Tablas y Widgets
                    950 => '#090d16', // Modo Oscuro: Fondo General de la Pantalla
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ==========================================
            // 1. INYECTAMOS BOTÓN EN TOPBAR Y TOUR GUIADO
            // ==========================================
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn(): string => Blade::render('@include("filament.admin.tour-button")')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn(): string => Blade::render('@include("filament.admin.tour-scripts")')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn(): string => Blade::render('
                    <style>
                        /* FIX: Forzar la barra superior por encima de los botones */
                        .fi-topbar { z-index: 60 !important; }
                        
                        /* FIX GLOBAL: Prevenir que cualquier imagen se desborde */
                        img { max-width: 100%; height: auto; }
                        
                        /* FIX: Dimensionamiento estricto de SVGs e iconos para evitar desbordes */
                        svg { max-width: 100%; }
                        .fi-sidebar-item-icon, 
                        .fi-sidebar-group-icon, 
                        .fi-sidebar-item-grouped-icon {
                            width: 20px !important;
                            height: 20px !important;
                            min-width: 20px !important;
                            min-height: 20px !important;
                        }
                        .fi-btn-icon, 
                        .fi-icon-btn-icon {
                            width: 18px !important;
                            height: 18px !important;
                        }
                        svg.w-4, svg[class*="w-4"] { width: 16px !important; height: 16px !important; min-width: 16px !important; min-height: 16px !important; flex-shrink: 0 !important; }
                        svg.w-5, svg[class*="w-5"] { width: 20px !important; height: 20px !important; min-width: 20px !important; min-height: 20px !important; flex-shrink: 0 !important; }
                        svg.w-6, svg[class*="w-6"] { width: 24px !important; height: 24px !important; min-width: 24px !important; min-height: 24px !important; flex-shrink: 0 !important; }
                        svg.w-8, svg[class*="w-8"] { width: 32px !important; height: 32px !important; min-width: 32px !important; min-height: 32px !important; flex-shrink: 0 !important; }
                        svg.w-10, svg[class*="w-10"] { width: 40px !important; height: 40px !important; min-width: 40px !important; min-height: 40px !important; flex-shrink: 0 !important; }
                        
                        /* FIX: Ancho completo al 100% para todo el panel, vistas y tarjetas de formularios */
                        .fi-main-ctn {
                            min-width: 0 !important;
                        }
                        .fi-main,
                        .fi-page,
                        .fi-page-content,
                        .fi-page-header-main-ctn,
                        .fi-section,
                        form.fi-form {
                            width: 100% !important;
                            max-width: 100% !important;
                        }
                        
                        /* ==========================================
                           SIDEBAR: Compacto y con línea divisoria
                           ========================================== */
                        .fi-sidebar { 
                            box-shadow: 2px 0 10px rgba(0,0,0,0.03); 
                            border-right: 1px solid #e2e8f0 !important; 
                        }
                        .fi-sidebar-nav {
                            padding-left: 0.75rem !important;
                            padding-right: 0.75rem !important;
                        }
                        
                        .dark .fi-sidebar { 
                            background-color: #111827 !important; /* Fondo nativo oscuro */
                            border-right: 1px solid #1f2937 !important; /* Línea divisoria sutil */
                        }
                        
                        /* FIX: Tablas Filament sin sobreposición entre columnas y acciones */
                        .fi-ta-content-ctn,
                        .fi-ta-content {
                            overflow-x: auto !important;
                        }
                        .fi-ta-cell,
                        .fi-ta-header-cell {
                            vertical-align: middle !important;
                        }
                        .fi-ta-actions {
                            gap: 0.45rem !important;
                            padding-left: 0.75rem !important;
                            white-space: nowrap !important;
                        }
                        
                        /* ==========================================
                           FIX: Tabla custom de Reportes
                           ========================================== */
                        .dark .tabla-excel th { background-color: #111827 !important; border-color: #374151 !important; }
                        .dark .tabla-excel td { border-color: #374151 !important; }
                        .dark .fila-par { background-color: #090d16 !important; }
                        .dark .fila-impar { background-color: #111827 !important; }
                    </style>
                ')
            );
    }
}