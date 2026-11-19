<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pengguna';
    }

    public function rules(): array
    {
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['Laki-laki', 'Perempuan', 'Lainnya'])],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'location' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'skills' => ['nullable', 'array'],
            'skills.*.id' => ['nullable', 'integer', Rule::exists('user_skills', 'id')->where('user_id', $this->user()->id)],
            'skills.*.skill_name' => ['nullable', 'string', 'max:255'],
            'educations' => ['nullable', 'array'],
            'educations.*.id' => ['nullable', 'integer', Rule::exists('user_educations', 'id')->where('user_id', $this->user()->id)],
            'educations.*.institution' => ['nullable', 'string', 'max:255'],
            'educations.*.major' => ['nullable', 'string', 'max:255'],
            'educations.*.start_year' => ['nullable', 'string', 'max:10'],
            'educations.*.end_year' => ['nullable', 'string', 'max:10'],
            'experiences' => ['nullable', 'array'],
            'experiences.*.id' => ['nullable', 'integer', Rule::exists('user_experiences', 'id')->where('user_id', $this->user()->id)],
            'experiences.*.position' => ['nullable', 'string', 'max:255'],
            'experiences.*.company' => ['nullable', 'string', 'max:255'],
            'experiences.*.period' => ['nullable', 'string', 'max:255'],
            'experiences.*.description' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.image' => 'Foto profil harus berupa gambar.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'location.required' => 'Lokasi wajib diisi.',
        ];
    }
}
