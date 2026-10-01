<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArsipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Dokumentasi::aktif()->orderByDesc('tanggal_dokumen');

        // Filter berdasarkan tipe
        $tipeAktif = null;
        if ($request->filled('tipe') && in_array($request->tipe, ['Foto', 'Video', 'Naskah', 'Audio'])) {
            $tipeAktif = $request->tipe;
            $query->where('tipe', $tipeAktif);
        }

        // Pencarian judul
        if ($request->filled('cari')) {
            $query->where('judul', 'like', '%' . $request->cari . '%');
        }

        $dokumentasis = $query->paginate(12)->withQueryString();

        return view('publik.arsip.index', compact('dokumentasis', 'tipeAktif'));
    }

    public function show(Dokumentasi $dokumentasi): View
    {
        abort_if($dokumentasi->status !== 'Aktif', 404);

        return view('publik.arsip.show', compact('dokumentasi'));
    }
}
