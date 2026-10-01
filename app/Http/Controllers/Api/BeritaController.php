<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Get all published news.
     */
    public function index(Request $request)
    {
        $berita = Berita::with('penulis:id,name')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('limit', 10));

        // Format response
        $berita->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'kategori' => $item->kategori,
                'thumbnail_url' => $item->thumbnail_url,
                'penulis' => $item->penulis->name ?? 'Admin',
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $item->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Berita',
            'data' => $berita
        ]);
    }

    /**
     * Get single news details.
     */
    public function show($id)
    {
        $berita = Berita::with('penulis:id,name')->find($id);

        if (!$berita || $berita->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Berita',
            'data' => [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'kategori' => $berita->kategori,
                'konten' => $berita->konten,
                'thumbnail_url' => $berita->thumbnail_url,
                'penulis' => $berita->penulis->name ?? 'Admin',
                'created_at' => $berita->created_at->format('Y-m-d H:i:s'),
                'created_at_human' => $berita->created_at->diffForHumans(),
            ]
        ]);
    }
}
