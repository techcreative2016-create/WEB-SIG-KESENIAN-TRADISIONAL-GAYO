<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sanggar'   => ['required', 'string', 'max:150'],
            'nama_acara'     => ['required', 'string', 'max:200'],
            'deskripsi'      => ['nullable', 'string', 'max:3000'],
            'jenis_kesenian' => ['required', Rule::in([
                'Didong', 'Tari Guel', 'Kerawang Gayo', 'Teganing', 'Campuran',
            ])],
            'tanggal'        => ['required', 'date'],
            'waktu_mulai'    => ['required', 'date_format:H:i'],
            'waktu_selesai'  => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'lokasi_acara'   => ['required', 'string', 'max:200'],
            'alamat_acara'   => ['nullable', 'string', 'max:300'],
            'latitude'       => ['nullable', 'numeric', 'between:-6,6'],
            'longitude'      => ['nullable', 'numeric', 'between:94,100'],
            'status'         => ['required', Rule::in([
                'Terjadwal', 'Berlangsung', 'Selesai', 'Dibatalkan',
            ])],
            'tiket_info'     => ['nullable', 'string', 'max:100'],
            'poster'         => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sanggar.required'   => 'Nama sanggar penampil wajib diisi.',
            'nama_sanggar.max'        => 'Nama sanggar maksimal 150 karakter.',
            'nama_acara.required'     => 'Nama acara wajib diisi.',
            'jenis_kesenian.required' => 'Jenis kesenian wajib dipilih.',
            'jenis_kesenian.in'       => 'Pilih jenis kesenian yang valid.',
            'tanggal.required'        => 'Tanggal acara wajib diisi.',
            'tanggal.date'            => 'Format tanggal tidak valid.',
            'waktu_mulai.required'    => 'Waktu mulai wajib diisi.',
            'waktu_mulai.date_format' => 'Format waktu mulai harus HH:MM (contoh: 19:30).',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'lokasi_acara.required'   => 'Nama lokasi acara wajib diisi.',
            'status.required'         => 'Status jadwal wajib dipilih.',
            'poster.image'            => 'File poster harus berupa gambar.',
            'poster.max'              => 'Ukuran poster maksimal 4MB.',
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
    }
}
