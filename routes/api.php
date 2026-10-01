<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\JadwalPenampilanController;
use App\Http\Controllers\Api\TokoController;
use App\Http\Controllers\Api\SanggarController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\DokumentasiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API Routes
Route::prefix('v1')->group(function () {
    // Mobile Hero Banner API
    Route::get('/mobile-hero-banners', [\App\Http\Controllers\Api\MobileHeroBannerController::class, 'index']);

    // Berita API
    Route::get('/berita', [BeritaController::class, 'index']);
    Route::get('/berita/{id}', [BeritaController::class, 'show']);

    // Jadwal API
    Route::get('/jadwal', [JadwalPenampilanController::class, 'index']);
    Route::get('/jadwal/{id}', [JadwalPenampilanController::class, 'show']);

    // Toko API
    Route::get('/toko', [TokoController::class, 'index']);
    Route::get('/toko/{id}', [TokoController::class, 'show']);

    // Sanggar API
    Route::get('/sanggar', [SanggarController::class, 'index']);
    Route::get('/sanggar/{id}', [SanggarController::class, 'show']);

    // Chat API
    Route::get('/chat/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
});
