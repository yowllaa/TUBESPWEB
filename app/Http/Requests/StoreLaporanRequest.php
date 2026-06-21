<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'masyarakat';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'kategori_pelaporan_id' => 'required|exists:kategori_pelaporan,id',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul laporan wajib diisi.',
            'kategori_pelaporan_id.required' => 'Kategori pelaporan wajib dipilih.',
            'kategori_pelaporan_id.exists' => 'Kategori pelaporan tidak valid.',
            'deskripsi.required' => 'Deskripsi laporan wajib diisi.',
            'lokasi.required' => 'Detail lokasi wajib diisi.',
            'latitude.required' => 'Titik latitude wajib ditentukan pada peta.',
            'longitude.required' => 'Titik longitude wajib ditentukan pada peta.',
            'foto.required' => 'Foto bukti kejadian wajib diunggah.',
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ];
    }
}
