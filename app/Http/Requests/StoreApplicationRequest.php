<?php

namespace App\Http\Requests;

use App\Models\JobListing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $job = $this->route('job');

        $this->merge([
            'job_listing_id' => $job instanceof JobListing ? $job->id : $job,
        ]);
    }

    public function authorize(): bool
    {
        return $this->user()?->role === 'pengguna';
    }

    public function rules(): array
    {
        return [
            'job_listing_id' => [
                'required',
                Rule::unique('applications', 'job_listing_id')
                    ->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'domicile' => ['required', 'string', 'max:255'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'job_listing_id.unique' => 'Anda sudah pernah melamar posisi ini.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor handphone wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'domicile.required' => 'Lokasi domisili wajib diisi.',
            'cv.required' => 'Silakan unggah CV dalam format PDF.',
            'cv.mimes' => 'CV harus berupa file PDF.',
            'cv.max' => 'Ukuran CV maksimal 2 MB.',
        ];
    }
}
