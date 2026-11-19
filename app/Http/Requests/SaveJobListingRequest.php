<?php

namespace App\Http\Requests;

use App\Models\JobListing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveJobListingRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'type' => $this->route('type'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function authorize(): bool
    {
        $job = $this->route('job');

        return $job instanceof JobListing
            ? $this->user()?->can('update', $job) ?? false
            : $this->user()?->can('create', JobListing::class) ?? false;
    }

    public function rules(): array
    {
        $type = $this->route('type');

        return [
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'location' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['lowongan', 'magang']), Rule::in([$type])],
            'work_system' => ['required', 'string', 'max:100'],
            'duration' => [$type === 'magang' ? 'required' : 'nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'description' => ['required', 'string', 'max:20000'],
            'qualifications' => ['required', 'string', 'max:10000'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'terbit', 'ditutup'])],
            'is_featured' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul posisi wajib diisi.',
            'title.string' => 'Judul posisi harus berupa teks.',
            'title.max' => 'Judul posisi maksimal 255 karakter.',
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'company_name.string' => 'Nama perusahaan harus berupa teks.',
            'company_name.max' => 'Nama perusahaan maksimal 255 karakter.',
            'company_logo.image' => 'Logo harus berupa berkas gambar.',
            'company_logo.mimes' => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'company_logo.max' => 'Ukuran logo maksimal 2 MB.',
            'location.required' => 'Lokasi wajib diisi.',
            'location.string' => 'Lokasi harus berupa teks.',
            'location.max' => 'Lokasi maksimal 255 karakter.',
            'type.required' => 'Tipe lowongan wajib dipilih.',
            'type.in' => 'Tipe lowongan tidak sesuai dengan halaman yang sedang dibuka.',
            'work_system.required' => 'Sistem kerja wajib diisi.',
            'work_system.string' => 'Sistem kerja harus berupa teks.',
            'work_system.max' => 'Sistem kerja maksimal 100 karakter.',
            'duration.required' => 'Durasi wajib diisi untuk program magang.',
            'duration.string' => 'Durasi harus berupa teks.',
            'duration.max' => 'Durasi maksimal 100 karakter.',
            'department.string' => 'Departemen harus berupa teks.',
            'department.max' => 'Departemen maksimal 255 karakter.',
            'salary_min.integer' => 'Gaji minimum harus berupa angka bulat.',
            'salary_min.min' => 'Gaji minimum tidak boleh kurang dari 0.',
            'salary_max.integer' => 'Gaji maksimum harus berupa angka bulat.',
            'salary_max.min' => 'Gaji maksimum tidak boleh kurang dari 0.',
            'salary_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
            'description.required' => 'Deskripsi pekerjaan wajib diisi.',
            'description.string' => 'Deskripsi pekerjaan harus berupa teks.',
            'description.max' => 'Deskripsi pekerjaan terlalu panjang.',
            'qualifications.required' => 'Kualifikasi wajib diisi.',
            'qualifications.string' => 'Kualifikasi harus berupa teks.',
            'qualifications.max' => 'Kualifikasi terlalu panjang.',
            'deadline.date' => 'Deadline harus berupa tanggal yang valid.',
            'status.required' => 'Status lowongan wajib dipilih.',
            'status.in' => 'Status lowongan tidak valid.',
            'is_featured.required' => 'Pilihan status unggulan tidak terkirim.',
            'is_featured.boolean' => 'Pilihan status unggulan tidak valid.',
        ];
    }
}
