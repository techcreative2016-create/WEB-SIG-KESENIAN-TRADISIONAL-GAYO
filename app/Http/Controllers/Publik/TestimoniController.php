<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;

class TestimoniController extends Controller
{
    /**
     * Simpan testimoni baru dari pengunjung.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:100',
            'asal'   => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'pesan'  => 'required|string|max:500',
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'rating.required' => 'Silakan pilih rating bintang.',
            'pesan.required'  => 'Pesan/ulasan wajib diisi.',
            'pesan.max'       => 'Pesan maksimal 500 karakter.',
        ]);

        // Daftar kata kasar untuk filter auto-moderasi
        $badWords = ['anjing', 'babi', 'monyet', 'bangsat', 'tolol', 'goblok', 'bego', 'idiot', 'bajingan', 'kampret', 'ngentot', 'memek', 'kontol', 'pantek', 'pukimak'];
        
        $pesanLower = strtolower($validated['pesan']);
        $containsBadWord = false;
        
        foreach ($badWords as $word) {
            // Gunakan preg_match untuk mencocokkan kata utuh
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $pesanLower)) {
                $containsBadWord = true;
                break;
            }
        }

        // Algoritma Penentuan Status
        $status = 'menunggu';
        $successMessage = 'Terima kasih! Ulasan Anda sedang menunggu persetujuan admin karena sistem mendeteksi kata kurang pantas atau rating rendah.';
        
        if ($validated['rating'] >= 4 && !$containsBadWord) {
            $status = 'disetujui';
            $successMessage = 'Terima kasih! Ulasan Anda sangat bagus dan langsung ditampilkan di halaman utama.';
        } elseif (!$containsBadWord) {
            $successMessage = 'Terima kasih! Ulasan Anda sedang direview oleh admin untuk ditampilkan.';
        }

        $validated['status'] = $status;

        Testimoni::create($validated);

        return back()->with('testimoni_sukses', $successMessage);
    }
}
