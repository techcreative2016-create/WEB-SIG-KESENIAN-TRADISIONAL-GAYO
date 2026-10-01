<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * SanggarRequest — Validasi form tambah & edit Sanggar.
 *
 * Dipakai oleh SanggarController::store() dan ::update().
 */
class SanggarRequest extends FormRequest
{
    /**
     * Hanya admin yang boleh mengakses form ini.
     */
    public function authorize(): bool
    {
        return true; // Ganti: auth()->check() setelah Breeze diinstall
    }

    /**
     * Aturan validasi untuk form sanggar.
     */
    public function rules(): array
    {
        // Untuk update: slug harus unik KECUALI milik sanggar yang sedang diedit
        $sanggar = $this->route('sanggar'); // Instance Sanggar dari route model binding

        return [
            // ----- Informasi Utama -----
            'nama_sanggar' => ['required', 'string', 'max:150'],

            'slug' => [
                'nullable',
                'string',
                'max:180',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('sanggars', 'slug')->ignore($sanggar?->id),
            ],

            'kategori' => ['required', Rule::in(['Tari', 'Musik', 'Kriya'])],

            'deskripsi' => ['nullable', 'string', 'max:5000'],

            // ----- Lokasi -----
            'alamat'    => ['required', 'string', 'max:300'],

            'kabupaten' => ['required', Rule::in(['Bener Meriah', 'Aceh Tengah'])],

            'kecamatan' => ['nullable', 'string', 'max:100'],

            // ----- Koordinat GPS — Validasi range Aceh -----
            'latitude' => [
                'required',
                'numeric',
                'between:-6,6',     // Rentang latitude Provinsi Aceh
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:94,100',   // Rentang longitude Provinsi Aceh
            ],

            // ----- Kontak -----
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email', 'max:150'],
            'instagram'  => ['nullable', 'string', 'max:80'],

            // ----- Status -----
            'status' => ['required', Rule::in(['Aktif', 'Vakum'])],

            // ----- Pengurus / Tokoh -----
            'nama_pimpinan' => ['nullable', 'string', 'max:150'],
            'peran_pimpinan' => ['nullable', 'string', 'max:150'],
            'nama_koreografer' => ['nullable', 'string', 'max:150'],
            'peran_koreografer' => ['nullable', 'string', 'max:150'],

            // ----- Foto Utama -----
            'foto_utama' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072', // Maks 3MB
            ],

            // ----- Galeri Tambahan (multiple upload) -----
            'galeri'   => ['nullable', 'array', 'max:10'],
            'galeri.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Pesan error dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_sanggar.required'  => 'Nama sanggar wajib diisi.',
            'nama_sanggar.max'       => 'Nama sanggar maksimal 150 karakter.',
            'slug.regex'             => 'Slug hanya boleh mengandung huruf kecil, angka, dan tanda hubung (-).',
            'slug.unique'            => 'Slug ini sudah digunakan sanggar lain. Gunakan nama yang berbeda.',
            'kategori.required'      => 'Kategori kesenian wajib dipilih.',
            'kategori.in'            => 'Kategori harus salah satu dari: Tari, Musik, atau Kriya.',
            'alamat.required'        => 'Alamat sanggar wajib diisi.',
            'kabupaten.required'     => 'Kabupaten wajib dipilih.',
            'kabupaten.in'           => 'Pilih kabupaten yang valid.',
            'latitude.required'      => 'Koordinat latitude wajib diisi. Tandai lokasi di peta.',
            'latitude.between'       => 'Latitude tidak valid untuk wilayah Aceh (antara -6 s/d 6).',
            'longitude.required'     => 'Koordinat longitude wajib diisi. Tandai lokasi di peta.',
            'longitude.between'      => 'Longitude tidak valid untuk wilayah Aceh (antara 94 s/d 100).',
            'email.email'            => 'Format email tidak valid.',
            'status.required'        => 'Status sanggar wajib dipilih.',
            'status.in'              => 'Status harus Aktif atau Vakum.',
            'foto_utama.image'       => 'File foto harus berupa gambar (JPG, PNG, WebP).',
            'foto_utama.max'         => 'Ukuran foto utama maksimal 3MB.',
            'galeri.max'             => 'Maksimal 10 foto galeri sekaligus.',
            'galeri.*.image'         => 'Setiap file galeri harus berupa gambar.',
            'galeri.*.max'           => 'Setiap foto galeri maksimal 5MB.',
        ];
    }

    /**
     * Preprocessing data sebelum validasi.
     * Auto-generate slug dari nama jika slug dikosongkan.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('latitude')) {
            $this->merge([
                'latitude' => str_replace(',', '.', $this->latitude),
            ]);
        }

        if ($this->filled('longitude')) {
            $this->merge([
                'longitude' => str_replace(',', '.', $this->longitude),
            ]);
        }

        if (empty($this->slug) && $this->filled('nama_sanggar')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->nama_sanggar),
            ]);
        }
    }
}
