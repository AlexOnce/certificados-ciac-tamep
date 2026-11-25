<?php

use App\Http\Controllers\VerificacionCertificadoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

// Endpoints públicos oficiales de verificación (con protección contra fuerza bruta y DoS)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/verificar/{codigo}', VerificacionCertificadoController::class)
        ->name('certificados.verificar');
    Route::get('/qr/{codigo}', [VerificacionCertificadoController::class, 'qr'])
        ->name('certificados.qr');
});

// Endpoints internos de exportación y plantillas (restringidos exclusivamente a usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/certificados/planilla-qr', [VerificacionCertificadoController::class, 'planilla'])
        ->name('certificados.planilla');
    Route::get('/certificados/exportar-excel-qr', [VerificacionCertificadoController::class, 'exportarExcelQr'])
        ->name('certificados.excel');
    Route::get('/certificados/descargar-plantilla', [VerificacionCertificadoController::class, 'descargarPlantilla'])
        ->name('certificados.plantilla');
});
