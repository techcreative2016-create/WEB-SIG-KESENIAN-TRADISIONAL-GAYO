<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Sanggar;
use App\Models\Toko;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PetaController — Controller utama untuk halaman peta interaktif GIS.
 *
 * Bertanggung jawab atas:
 * 1. Merender halaman peta (Blade view)
 * 2. Menyediakan endpoint JSON berisi marker untuk Google Maps
 *    dengan dukungan filter kategori, kabupaten, dan tipe lokasi.
 */
class PetaController extends Controller
{
    // =========================================================================
    // Halaman Publik
    // =========================================================================

    /**
     * Tampilkan halaman peta interaktif GIS.
     *
     * GET /peta
     */
    public function index(): View
    {
        // Statistik ringkas untuk panel info peta
        $statistik = [
            'total_sanggar'       => Sanggar::aktif()->count(),
            'total_toko'          => Toko::buka()->count(),
            'total_bener_meriah'  => Sanggar::byKabupaten('Bener Meriah')->count()
                                   + Toko::byKabupaten('Bener Meriah')->count(),
        ];

        return view('publik.peta.index', compact('statistik'));
    }

    // =========================================================================
    // API Endpoint — JSON untuk Google Maps JavaScript API
    // =========================================================================

    /**
     * Kembalikan data semua marker dalam format JSON.
     * Digunakan oleh Google Maps API secara asinkron (AJAX/Fetch).
     *
     * GET /api/peta/markers
     *
     * Query Parameters:
     *   ?tipe      = sanggar|toko|semua        (default: semua)
     *   ?kategori  = Tari|Musik|Kriya          (khusus sanggar)
     *   ?kabupaten = Bener Meriah              (opsional)
     *   ?status    = Aktif|Vakum               (khusus sanggar, opsional)
     */
    public function markers(Request $request): JsonResponse
    {
        $tipe      = $request->query('tipe', 'semua');
        $kategori  = $request->query('kategori');
        $kabupaten = $request->query('kabupaten');
        $status    = $request->query('status');

        $markers = collect();

        // ------------------------------------------------------------------
        // Ambil marker Sanggar Seni
        // ------------------------------------------------------------------
        if (in_array($tipe, ['semua', 'sanggar'])) {
            $querySanggar = Sanggar::query()
                ->select([
                    'id', 'nama_sanggar', 'slug', 'kategori',
                    'kabupaten', 'kecamatan', 'alamat',
                    'latitude', 'longitude',
                    'status', 'foto_utama', 'no_telepon',
                ]);

            // Terapkan filter opsional
            if ($kategori) {
                $querySanggar->byKategori($kategori);
            }
            if ($kabupaten) {
                $querySanggar->byKabupaten($kabupaten);
            }
            if ($status) {
                $querySanggar->where('status', $status);
            }

            // Map ke format marker peta
            $sanggars = $querySanggar->get()->map(fn($s) => $s->toMapMarker());
            $markers  = $markers->merge($sanggars);
        }

        // ------------------------------------------------------------------
        // Ambil marker Toko Kesenian
        // ------------------------------------------------------------------
        if (in_array($tipe, ['semua', 'toko'])) {
            $queryToko = Toko::query()
                ->select([
                    'id', 'nama_toko', 'slug', 'jenis_produk',
                    'kabupaten', 'kecamatan', 'alamat',
                    'latitude', 'longitude',
                    'status', 'foto_utama', 'no_telepon', 'jam_buka',
                ]);

            if ($kabupaten) {
                $queryToko->byKabupaten($kabupaten);
            }
            // Filter jenis produk toko sesuai kategori (jika ada)
            if ($kategori) {
                $queryToko->byJenisProduk($kategori);
            }

            $tokos   = $queryToko->get()->map(fn($t) => $t->toMapMarker());
            $markers = $markers->merge($tokos);
        }

        // ------------------------------------------------------------------
        // Ambil marker Jadwal Penampilan (Pertunjukan)
        // ------------------------------------------------------------------
        if (in_array($tipe, ['semua', 'pertunjukan'])) {
            $queryJadwal = \App\Models\JadwalPenampilan::query()
                ->whereIn('status', ['Terjadwal', 'Berlangsung'])
                ->select([
                    'id', 'nama_acara', 'jenis_kesenian',
                    'lokasi_acara', 'alamat_acara',
                    'latitude', 'longitude',
                    'status', 'poster', 'tanggal', 'waktu_mulai', 'waktu_selesai',
                ]);

            if ($kategori) {
                $queryJadwal->byJenisKesenian($kategori);
            }

            $jadwals = $queryJadwal->get()->map(fn($j) => $j->toMapMarker());
            $markers = $markers->merge($jadwals);
        }

        // ------------------------------------------------------------------
        // Kembalikan respons JSON dengan header cache
        // ------------------------------------------------------------------
        return response()->json([
            'success' => true,
            'total'   => $markers->count(),
            'data'    => $markers->values(), // Reset keys agar jadi array bersih
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate'); // Selalu ambil data terbaru
    }

    /**
     * Kembalikan detail satu marker (digunakan untuk InfoWindow Google Maps).
     *
     * GET /api/peta/detail/{tipe}/{id}
     */
    public function detail(Request $request, string $tipe, int $id): JsonResponse
    {
        if ($tipe === 'sanggar') {
            $item = Sanggar::with(['galeri' => fn($q) => $q->foto()->limit(6)])
                           ->findOrFail($id);

            return response()->json([
                'success' => true,
                'tipe'    => 'sanggar',
                'data'    => [
                    ...$item->toMapMarker(),
                    'deskripsi'  => $item->deskripsi,
                    'no_telepon' => $item->no_telepon,
                    'instagram'  => $item->instagram,
                    'galeri'     => $item->galeri->map(fn($g) => [
                        'url'    => $g->url,
                        'judul'  => $g->judul,
                    ]),
                ],
            ]);
        }

        if ($tipe === 'toko') {
            $item = Toko::with(['galeri' => fn($q) => $q->foto()->limit(6)])
                        ->findOrFail($id);

            return response()->json([
                'success' => true,
                'tipe'    => 'toko',
                'data'    => [
                    ...$item->toMapMarker(),
                    'deskripsi'  => $item->deskripsi,
                    'no_telepon' => $item->no_telepon,
                    'jam_buka'   => $item->jam_buka,
                    'galeri'     => $item->galeri->map(fn($g) => [
                        'url'   => $g->url,
                        'judul' => $g->judul,
                    ]),
                ],
            ]);
        }

        if ($tipe === 'pertunjukan') {
            $item = \App\Models\JadwalPenampilan::findOrFail($id);

            return response()->json([
                'success' => true,
                'tipe'    => 'pertunjukan',
                'data'    => [
                    ...$item->toMapMarker(),
                    'deskripsi'  => $item->deskripsi,
                    'no_telepon' => '-',
                    'jam_buka'   => \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') . ' - ' . ($item->waktu_selesai ? \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') : 'Selesai'),
                    'galeri'     => [],
                ],
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tipe tidak valid.'], 422);
    }
}
