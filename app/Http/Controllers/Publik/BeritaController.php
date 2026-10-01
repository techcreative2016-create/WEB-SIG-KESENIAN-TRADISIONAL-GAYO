<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::where('status', 'approved')->latest()->paginate(9);
        return view('publik.berita.index', compact('beritas'));
    }

    public function show(Berita $berita)
    {
        if($berita->status !== 'approved') {
            abort(404);
        }
        return view('publik.berita.show', compact('berita'));
    }
}
