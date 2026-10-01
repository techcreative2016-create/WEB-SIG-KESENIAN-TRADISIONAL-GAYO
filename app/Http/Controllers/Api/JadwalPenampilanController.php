<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalPenampilan;
use Illuminate\Http\Request;

class JadwalPenampilanController extends Controller
{
    /**
     * Get list of schedules.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all, hari_ini, mendatang
        $query = JadwalPenampilan::with('sanggar:id,nama_sanggar,foto_utama');

        if ($filter === 'hari_ini') {
            $query->hariIni();
        } elseif ($filter === 'mendatang') {
            // Hanya tampilkan yang Terjadwal & tanggalnya >= hari ini
            $query->where('status', 'Terjadwal')
                  ->whereDate('tanggal', '>=', now()->toDateString())
                  ->orderBy('tanggal', 'asc')
                  ->orderBy('waktu_mulai', 'asc');
        } elseif ($filter === 'beranda') {
            // Khusus beranda: ambil 5 jadwal mendatang + yang berlangsung hari ini
            $query->where('status', 'Terjadwal')
                  ->whereDate('tanggal', '>=', now()->toDateString())
                  ->orderBy('tanggal', 'asc')
                  ->limit(5);
        } else {
            // Only show scheduled/completed, not cancelled unless specifically requested
            $query->where('status', '!=', 'Dibatalkan')
                  ->orderBy('tanggal', 'asc')
                  ->orderBy('waktu_mulai', 'asc');
        }

        $jadwals = $query->paginate($request->get('limit', 10));

        $jadwals->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'nama_acara' => $item->nama_acara,
                'jenis_kesenian' => $item->jenis_kesenian,
                'tanggal' => $item->tanggal->format('Y-m-d'),
                'waktu_mulai' => $item->waktu_mulai->format('H:i'),
                'waktu_selesai' => $item->waktu_selesai->format('H:i'),
                'lokasi_acara' => $item->lokasi_acara,
                'status' => $item->status,
                'poster_url' => $item->poster_url,
                'sanggar' => $item->sanggar ? [
                    'id' => $item->sanggar->id,
                    'nama_sanggar' => $item->sanggar->nama_sanggar,
                    'logo_url' => $item->sanggar->foto_utama_url,
                ] : null,
                'is_hari_ini' => $item->is_hari_ini,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Jadwal Penampilan',
            'data' => $jadwals
        ]);
    }

    /**
     * Get single schedule details.
     */
    public function show($id)
    {
        $jadwal = JadwalPenampilan::with('sanggar:id,nama_sanggar,foto_utama')->find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Jadwal Penampilan',
            'data' => [
                'id' => $jadwal->id,
                'nama_acara' => $jadwal->nama_acara,
                'deskripsi' => $jadwal->deskripsi,
                'jenis_kesenian' => $jadwal->jenis_kesenian,
                'tanggal' => $jadwal->tanggal->format('Y-m-d'),
                'waktu_mulai' => $jadwal->waktu_mulai->format('H:i'),
                'waktu_selesai' => $jadwal->waktu_selesai->format('H:i'),
                'lokasi_acara' => $jadwal->lokasi_acara,
                'alamat_acara' => $jadwal->alamat_acara,
                'latitude' => $jadwal->latitude,
                'longitude' => $jadwal->longitude,
                'status' => $jadwal->status,
                'tiket_info' => $jadwal->tiket_info,
                'poster_url' => $jadwal->poster_url,
                'sanggar' => $jadwal->sanggar ? [
                    'id' => $jadwal->sanggar->id,
                    'nama_sanggar' => $jadwal->sanggar->nama_sanggar,
                    'logo_url' => $jadwal->sanggar->foto_utama_url,
                ] : null,
            ]
        ]);
    }
}
