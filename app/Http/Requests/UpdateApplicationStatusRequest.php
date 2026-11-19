<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('updateStatus', $this->route('application')) ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['Diproses', 'Diterima', 'Ditolak', 'Dibatalkan'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status lamaran wajib dipilih.',
            'status.in' => 'Status lamaran tidak valid.',
            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan maksimal 5000 karakter.',
        ];
    }
}
