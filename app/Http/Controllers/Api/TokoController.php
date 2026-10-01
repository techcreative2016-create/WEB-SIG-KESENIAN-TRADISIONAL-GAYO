<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    /**
     * Get all shops.
     */
    public function index(Request $request)
    {
        $query = Toko::query();

        if ($request->has('jenis_produk')) {
            $query->byJenisProduk($request->jenis_produk);
        }

        if ($request->has('kabupaten')) {
            $query->byKabupaten($request->kabupaten);
        }

        if ($request->has('status') && $request->status === 'buka') {
            $query->buka();
        }

        $tokos = $query->orderBy('nama_toko', 'asc')->paginate($request->get('limit', 10));

        $tokos->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'nama_toko' => $item->nama_toko,
                'slug' => $item->slug,
                'jenis_produk' => $item->jenis_produk,
                'kabupaten' => $item->kabupaten,
                'kecamatan' => $item->kecamatan,
                'alamat' => $item->alamat,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'no_telepon' => $item->no_telepon,
                'deskripsi' => $item->deskripsi,
                'status' => $item->status,
                'foto_utama_url' => $item->foto_utama_url,
                'jam_buka' => $item->jam_buka,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Toko',
            'data' => $tokos
        ]);
    }

    /**
     * Get single shop details.
     */
    public function show($id)
    {
        $toko = Toko::with('galeri')->find($id);

        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Toko tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Toko',
            'data' => [
                'id' => $toko->id,
                'nama_toko' => $toko->nama_toko,
                'slug' => $toko->slug,
                'jenis_produk' => $toko->jenis_produk,
                'deskripsi' => $toko->deskripsi,
                'alamat' => $toko->alamat,
                'kabupaten' => $toko->kabupaten,
                'kecamatan' => $toko->kecamatan,
                'latitude' => $toko->latitude,
                'longitude' => $toko->longitude,
                'no_telepon' => $toko->no_telepon,
                'jam_buka' => $toko->jam_buka,
                'status' => $toko->status,
                'foto_utama_url' => $toko->foto_utama_url,
                'galeri' => $toko->galeri->map(function ($g) {
                    return [
                        'id' => $g->id,
                        'file_url' => $g->file_url,
                        'tipe_file' => $g->tipe_file,
                        'keterangan' => $g->keterangan,
                    ];
                }),
            ]
        ]);
    }
}
