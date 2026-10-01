<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\JadwalPenampilan;
use Carbon\Carbon;
use Illuminate\View\View;

/**
 * JadwalController — Halaman jadwal pertunjukan seni live hari ini.
 */
class JadwalController extends Controller
{
    /**
     * Tampilkan jadwal penampilan hari ini.
     *
     * GET /jadwal
     */
    public function index(): View
    {
        // Jadwal hari ini — eager load sanggar untuk efisiensi
        $jadwalHariIni = JadwalPenampilan::hariIni()
            ->paginate(10);

        // Jadwal mendatang (preview 5 acara ke depan)
        $jadwalMendatang = JadwalPenampilan::mendatang()
            ->limit(5)
            ->get();

        $tanggalHariIni = Carbon::today()->translatedFormat('l, d F Y');

        // Semua jadwal untuk kalender Javascript (Bisa dioptimasi filter by month, tapi get all untuk simple sync dulu)
        $semuaJadwal = JadwalPenampilan::where('status', '!=', 'Dibatalkan')->get();

        return view('publik.jadwal.index', compact(
            'jadwalHariIni',
            'jadwalMendatang',
            'tanggalHariIni',
            'semuaJadwal'
        ));
    }

    /**
     * Tampilkan detail jadwal penampilan.
     *
     * GET /jadwal/{jadwal}
     */
    public function show(JadwalPenampilan $jadwal): View
    {
        return view('publik.jadwal.show', compact('jadwal'));
    }
}
