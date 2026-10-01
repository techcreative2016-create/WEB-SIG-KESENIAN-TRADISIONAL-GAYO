<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TokoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $toko = $this->route('toko');

        return [
            'nama_toko'    => ['required', 'string', 'max:150'],
            'slug'         => [
                'nullable', 'string', 'max:180', 'regex:/^[a-z0-9\-]+$/',
                Rule::unique('tokos', 'slug')->ignore($toko?->id),
            ],
            'jenis_produk' => ['required', Rule::in([
                'Kerawang Gayo', 'Alat Musik', 'Pakaian Adat', 'Kerajinan Tangan', 'Campuran',
            ])],
            'deskripsi'    => ['nullable', 'string', 'max:5000'],
            'alamat'       => ['required', 'string', 'max:300'],
            'kabupaten'    => ['required', Rule::in(['Bener Meriah', 'Aceh Tengah'])],
            'kecamatan'    => ['nullable', 'string', 'max:100'],
            'latitude'     => ['required', 'numeric', 'between:-6,6'],
            'longitude'    => ['required', 'numeric', 'between:94,100'],
            'no_telepon'   => ['nullable', 'string', 'max:20'],
            'jam_buka'     => ['nullable', 'string', 'max:50'],
            'status'       => ['required', Rule::in(['Buka', 'Tutup'])],
            'foto_utama'   => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'galeri'       => ['nullable', 'array', 'max:10'],
            'galeri.*'     => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_toko.required'    => 'Nama toko wajib diisi.',
            'jenis_produk.required' => 'Jenis produk wajib dipilih.',
            'jenis_produk.in'       => 'Pilih jenis produk yang valid.',
            'alamat.required'       => 'Alamat toko wajib diisi.',
            'kabupaten.required'    => 'Kabupaten wajib dipilih.',
            'latitude.required'     => 'Koordinat latitude wajib diisi.',
            'latitude.between'      => 'Latitude tidak valid untuk wilayah Aceh.',
            'longitude.required'    => 'Koordinat longitude wajib diisi.',
            'longitude.between'     => 'Longitude tidak valid untuk wilayah Aceh.',
            'status.required'       => 'Status toko wajib dipilih.',
            'foto_utama.image'      => 'File foto harus berupa gambar.',
            'foto_utama.max'        => 'Foto utama maksimal 3MB.',
        ];
    }

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

        if (empty($this->slug) && $this->filled('nama_toko')) {
            $this->merge(['slug' => \Illuminate\Support\Str::slug($this->nama_toko)]);
        }
    }
}
