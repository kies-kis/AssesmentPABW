<?php

namespace Modules\TitikDrainase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitikDrainaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nama_titik' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'kedalaman_cm' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_titik.required' => 'Nama titik drainase wajib diisi.',
            'nama_titik.max' => 'Nama titik drainase maksimal 100 karakter.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kecamatan.max' => 'Kecamatan maksimal 100 karakter.',
            'kedalaman_cm.integer' => 'Kedalaman harus berupa bilangan bulat.',
            'kedalaman_cm.min' => 'Kedalaman tidak boleh negatif.',
        ];
    }
}
