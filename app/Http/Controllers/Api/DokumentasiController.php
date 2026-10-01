<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Dokumentasi::aktif()->latest('tanggal_dokumen');
        
        if ($request->filled('tipe') && in_array($request->tipe, ['Foto', 'Video', 'Naskah', 'Audio'])) {
            $query->where('tipe', $request->tipe);
        }

        $dokumentasis = $query->get()->map(function ($dok) {
            return [
                'id' => $dok->id,
                'judul' => $dok->judul,
                'tipe' => $dok->tipe,
                'deskripsi' => $dok->deskripsi,
                'tanggal_dokumen' => $dok->tanggal_dokumen->format('Y-m-d'),
                'file_url' => $dok->file_url,
                'thumbnail_url' => $dok->thumbnail_url,
                'url_eksternal' => $dok->url_eksternal,
                'icon' => $dok->icon,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $dokumentasis
        ]);
    }
}
