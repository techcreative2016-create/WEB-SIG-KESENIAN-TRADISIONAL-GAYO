<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sanggar;
use Illuminate\Http\Request;

class SanggarController extends Controller
{
    /**
     * Get all studios (Sanggar).
     */
    public function index(Request $request)
    {
        $query = Sanggar::query();

        if ($request->has('kategori')) {
            $query->byKategori($request->kategori);
        }

        if ($request->has('kabupaten')) {
            $query->byKabupaten($request->kabupaten);
        }

        if ($request->has('status') && $request->status === 'aktif') {
            $query->aktif();
        }

        $sanggars = $query->orderBy('nama_sanggar', 'asc')->paginate($request->get('limit', 10));

        $sanggars->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'nama_sanggar' => $item->nama_sanggar,
                'slug' => $item->slug,
                'kategori' => $item->kategori,
                'kabupaten' => $item->kabupaten,
                'kecamatan' => $item->kecamatan,
                'alamat' => $item->alamat,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'no_telepon' => $item->no_telepon,
                'deskripsi' => $item->deskripsi,
                'status' => $item->status,
                'foto_utama_url' => $item->foto_utama_url,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Sanggar Seni',
            'data' => $sanggars
        ]);
    }

    /**
     * Get single studio details.
     */
    public function show($id)
    {
        $sanggar = Sanggar::with('galeri')->find($id);

        if (!$sanggar) {
            return response()->json([
                'success' => false,
                'message' => 'Sanggar tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Sanggar',
            'data' => [
                'id' => $sanggar->id,
                'nama_sanggar' => $sanggar->nama_sanggar,
                'slug' => $sanggar->slug,
                'kategori' => $sanggar->kategori,
                'deskripsi' => $sanggar->deskripsi,
                'alamat' => $sanggar->alamat,
                'kabupaten' => $sanggar->kabupaten,
                'kecamatan' => $sanggar->kecamatan,
                'latitude' => $sanggar->latitude,
                'longitude' => $sanggar->longitude,
                'no_telepon' => $sanggar->no_telepon,
                'email' => $sanggar->email,
                'instagram' => $sanggar->instagram,
                'status' => $sanggar->status,
                'foto_utama_url' => $sanggar->foto_utama_url,
                'galeri' => $sanggar->galeri->map(function ($g) {
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
