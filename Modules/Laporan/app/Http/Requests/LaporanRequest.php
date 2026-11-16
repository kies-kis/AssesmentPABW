<?php

namespace Modules\Laporan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Laporan\Models\Laporan;

class LaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'titik_drainase_id' => ['required', 'integer', 'exists:titik_drainases,id'],
            'tinggi_air_cm' => ['required', 'integer', 'min:0'],
            'status_kondisi' => ['required', Rule::in(array_keys(Laporan::STATUS_KONDISI))],
            'deskripsi' => ['required', 'string', 'max:2000'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'titik_drainase_id.required' => 'Titik drainase wajib dipilih.',
            'titik_drainase_id.exists' => 'Titik drainase tidak terdaftar.',
            'tinggi_air_cm.required' => 'Tinggi air wajib diisi.',
            'tinggi_air_cm.integer' => 'Tinggi air harus berupa bilangan bulat.',
            'tinggi_air_cm.min' => 'Tinggi air tidak boleh negatif.',
            'status_kondisi.required' => 'Status kondisi wajib dipilih.',
            'status_kondisi.in' => 'Status kondisi tidak valid.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal 2000 karakter.',
            'foto_bukti.image' => 'File harus berupa gambar.',
            'foto_bukti.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_bukti.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
