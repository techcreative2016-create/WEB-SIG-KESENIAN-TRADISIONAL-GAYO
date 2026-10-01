<?php

use App\Http\Controllers\Admin\SanggarController as AdminSanggarController;
use App\Http\Controllers\Admin\TokoController    as AdminTokoController;
use App\Http\Controllers\Admin\JadwalController  as AdminJadwalController;
use App\Http\Controllers\Publik\PetaController;
use App\Http\Controllers\Publik\JadwalController;
use App\Http\Controllers\Publik\ArsipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Master\MasterAdminController;
use App\Http\Controllers\Master\DokumentasiController as MasterDokumentasiController;
use App\Http\Controllers\Publik\ChatController;
use App\Http\Controllers\Publik\BeritaController as PublikBeritaController;
use App\Http\Controllers\Master\ChatAdminController;
use App\Http\Controllers\Publik\TestimoniController;
use App\Http\Controllers\Admin\TestimoniAdminController;
use App\Http\Controllers\Master\TestimoniMasterController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// RUTE PUBLIK — Pengunjung umum (tidak perlu login)
// =============================================================================

Route::get('/', function () {
    $agendaBudaya = \App\Models\JadwalPenampilan::whereIn('status', ['Terjadwal', 'Berlangsung'])->latest()->take(3)->get();
    $beritaTerbaru = \App\Models\Berita::where('status', 'approved')->latest()->take(3)->get();
    $dokumentasiTerbaru = \App\Models\Dokumentasi::where('status', 'Aktif')->latest()->take(6)->get();
    $testimonis = \App\Models\Testimoni::disetujui()->latest()->take(5)->get();
    return view('landing-page-utama.index', compact('agendaBudaya', 'beritaTerbaru', 'dokumentasiTerbaru', 'testimonis'));
})->middleware(\App\Http\Middleware\TrackVisitor::class)->name('publik.beranda');

Route::get('/force-logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
});

// Chat Visitor Routes
Route::prefix('chat')->name('publik.chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/messages', [ChatController::class, 'getMessages'])->name('messages');
    Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
});

// Redirect dashboard requests based on role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'master') {
            return redirect()->route('master.dashboard');
        }
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('publik.beranda');
    }
    return redirect()->route('login');
})->middleware('auth');

// Peta Interaktif
Route::get('/peta', [PetaController::class, 'index'])->name('publik.peta');

// Sanggar Publik
Route::get('/sanggar', function () {
    $sanggars = \App\Models\Sanggar::aktif()->latest()->paginate(12);
    return view('publik.sanggar.index', compact('sanggars'));
})->name('publik.sanggar.index');

Route::get('/sanggar/{slug}', function ($slug) {
    $sanggar = \App\Models\Sanggar::where('slug', $slug)
        ->with(['galeri'])
        ->firstOrFail();
    $dokumentasis = \App\Models\Dokumentasi::aktif()->latest()->take(6)->get();
    return view('publik.sanggar.show', compact('sanggar', 'dokumentasis'));
})->name('publik.sanggar.show');

// Toko Publik
Route::get('/toko', function () {
    $tokos = \App\Models\Toko::buka()->latest()->paginate(12);
    return view('publik.toko.index', compact('tokos'));
})->name('publik.toko.index');

Route::get('/toko/{slug}', function ($slug) {
    $toko = \App\Models\Toko::where('slug', $slug)->with('galeri')->firstOrFail();
    return view('publik.toko.show', compact('toko'));
})->name('publik.toko.show');

// Jadwal Penampilan Live
Route::get('/jadwal', [JadwalController::class, 'index'])->name('publik.jadwal');
Route::get('/jadwal/{jadwal}', [JadwalController::class, 'show'])->name('publik.jadwal.show');

// Arsip Dokumentasi
Route::get('/arsip', [ArsipController::class, 'index'])->name('publik.arsip');
Route::get('/arsip/{dokumentasi}', [ArsipController::class, 'show'])->name('publik.arsip.show');

// Berita / Portal Berita
Route::get('/berita', [PublikBeritaController::class, 'index'])->name('publik.berita.index');
Route::get('/berita/{berita}', [PublikBeritaController::class, 'show'])->name('publik.berita.show');

// Testimoni / Ulasan Pengunjung
Route::post('/testimoni', [TestimoniController::class, 'store'])->name('publik.testimoni.store');

// Download Arsip PDF
Route::get('/arsip/{dokumentasi}/download', function (\App\Models\Dokumentasi $dokumentasi) {
    abort_unless($dokumentasi->file_path && $dokumentasi->status === 'Aktif', 404);
    $path = storage_path('app/public/' . $dokumentasi->file_path);
    abort_unless(file_exists($path), 404);
    return response()->download($path, $dokumentasi->judul . '.' . pathinfo($path, PATHINFO_EXTENSION));
})->name('publik.arsip.download');

// =============================================================================
// RUTE API — Digunakan oleh Google Maps JavaScript API (AJAX/Fetch)
// =============================================================================

Route::prefix('api/peta')->name('api.peta.')->group(function () {
    Route::get('/markers', [PetaController::class, 'markers'])->name('markers');
    Route::get('/detail/{tipe}/{id}', [PetaController::class, 'detail'])
         ->where(['tipe' => 'sanggar|toko', 'id' => '[0-9]+'])
         ->name('detail');
});

// =============================================================================
// RUTE ADMIN — Terautentikasi (Breeze auth)
// =============================================================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin', 'anti.negatif'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'sanggar' => \App\Models\Sanggar::count(),
            'toko'    => \App\Models\Toko::count(),
            'jadwal'  => \App\Models\JadwalPenampilan::hariIni()->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    })->name('dashboard');

    // -------------------------------------------------------------------------
    // CRUD Sanggar
    // -------------------------------------------------------------------------
    Route::resource('sanggars', AdminSanggarController::class);

    // Restore sanggar yang di-soft delete
    Route::patch('sanggars/{id}/restore', [AdminSanggarController::class, 'restore'])
         ->name('sanggars.restore');

    // Manajemen Galeri Sanggar
    Route::post(
        'sanggars/{sanggar}/galeri',
        [AdminSanggarController::class, 'uploadGaleri']
    )->name('sanggars.galeri.upload');

    Route::delete(
        'sanggars/{sanggar}/galeri/{galeri}',
        [AdminSanggarController::class, 'hapusGaleri']
    )->name('sanggars.galeri.hapus');

    Route::patch(
        'sanggars/{sanggar}/galeri/{galeri}/utama',
        [AdminSanggarController::class, 'setFotoUtama']
    )->name('sanggars.galeri.utama');

    // -------------------------------------------------------------------------
    // CRUD Toko
    // -------------------------------------------------------------------------
    Route::resource('tokos', AdminTokoController::class);

    Route::patch('tokos/{id}/restore', [AdminTokoController::class, 'restore'])
         ->name('tokos.restore');

    Route::post('tokos/{toko}/galeri',   [AdminTokoController::class, 'uploadGaleri'])->name('tokos.galeri.upload');
    Route::delete('tokos/{toko}/galeri/{galeri}', [AdminTokoController::class, 'hapusGaleri'])->name('tokos.galeri.hapus');
    Route::patch('tokos/{toko}/galeri/{galeri}/utama', [AdminTokoController::class, 'setFotoUtama'])->name('tokos.galeri.utama');

    // -------------------------------------------------------------------------
    // CRUD Jadwal
    // -------------------------------------------------------------------------
    Route::resource('jadwal', AdminJadwalController::class);

    Route::patch('jadwal/{jadwal}/status', [AdminJadwalController::class, 'updateStatus'])
         ->name('jadwal.status');

    // -------------------------------------------------------------------------
    // CRUD Berita
    // -------------------------------------------------------------------------
    Route::resource('berita', \App\Http\Controllers\Admin\BeritaController::class);

    // -------------------------------------------------------------------------
    // Manajemen Testimoni
    // -------------------------------------------------------------------------
    Route::get('/testimoni', [TestimoniAdminController::class, 'index'])->name('testimoni.index');
    Route::patch('/testimoni/{testimoni}/status', [TestimoniAdminController::class, 'updateStatus'])->name('testimoni.status');
    Route::delete('/testimoni/{testimoni}', [TestimoniAdminController::class, 'destroy'])->name('testimoni.destroy');
    Route::get('/notif-count', [TestimoniAdminController::class, 'notifCount'])->name('notif.count');

    // -------------------------------------------------------------------------
    // Profil
    // -------------------------------------------------------------------------
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Breeze auth routes (login, register, logout, dll.)
require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', 'role:master'])->prefix('master')->name('master.')->group(function () {
    Route::get('/dashboard', [MasterAdminController::class, 'index'])->name('dashboard');
    Route::get('/admin/create', [MasterAdminController::class, 'createAdmin'])->name('admin.create');
    Route::post('/admin/create', [MasterAdminController::class, 'storeAdmin'])->name('admin.store');
    Route::post('/admin/{id}/toggle', [MasterAdminController::class, 'toggleStatus'])->name('admin.toggle');
    Route::get('/logs', [MasterAdminController::class, 'viewLogs'])->name('logs');
    
    // Konten Mobile
    Route::resource('mobile-hero', \App\Http\Controllers\Master\MobileHeroBannerController::class)->except(['show']);
    
    // Persetujuan Berita
    Route::get('/berita', [\App\Http\Controllers\Master\BeritaController::class, 'index'])->name('berita.index');
    Route::patch('/berita/{beritum}/status', [\App\Http\Controllers\Master\BeritaController::class, 'updateStatus'])->name('berita.status');
    Route::delete('/berita/{beritum}', [\App\Http\Controllers\Master\BeritaController::class, 'destroy'])->name('berita.destroy');

    // Statistik Pengunjung
    Route::get('/statistik-pengunjung', [\App\Http\Controllers\Master\VisitorController::class, 'index'])->name('visitor.stats');
    
    // Profil Master Admin
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat Master Admin Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatAdminController::class, 'index'])->name('index');
        Route::get('/{session_id}', [ChatAdminController::class, 'show'])->name('show');
        Route::post('/{session_id}', [ChatAdminController::class, 'reply'])->name('reply');
    });

    // Dokumentasi Seni (Master Admin Only)
    Route::delete('dokumentasi/bulk-destroy', [MasterDokumentasiController::class, 'bulkDestroy'])->name('dokumentasi.bulkDestroy');
    Route::resource('dokumentasi', MasterDokumentasiController::class);

    // Saran dan Ulasan
    Route::get('/testimoni', [TestimoniMasterController::class, 'index'])->name('testimoni.index');
    Route::patch('/testimoni/{testimoni}/status', [TestimoniMasterController::class, 'updateStatus'])->name('testimoni.status');
    Route::delete('/testimoni/{testimoni}', [TestimoniMasterController::class, 'destroy'])->name('testimoni.destroy');
    Route::get('/notif-count', [TestimoniMasterController::class, 'notifCount'])->name('notif.count');
});
