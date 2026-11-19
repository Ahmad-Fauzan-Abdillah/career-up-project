<?php

namespace App\Http\Requests;

use App\Models\Training;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $training = $this->route('training');

        return $training instanceof Training
            ? $this->user()?->can('update', $training) ?? false
            : $this->user()?->can('create', Training::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(['Gratis', 'Premium'])],
            'schedule_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'zoom_link' => ['required', 'url', 'max:2048'],
            'mentor_name' => ['required', 'string', 'max:255'],
            'mentor_role' => ['required', 'string', 'max:255'],
            'mentor_avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration' => ['required', 'string', 'max:100'],
            'level' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:20000'],
            'syllabus' => ['required', 'string', 'max:10000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['draft', 'terbit'])],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul pelatihan wajib diisi.',
            'title.string' => 'Judul pelatihan harus berupa teks.',
            'title.max' => 'Judul pelatihan maksimal 255 karakter.',
            'category.required' => 'Kategori pelatihan wajib dipilih.',
            'category.in' => 'Kategori pelatihan tidak valid.',
            'schedule_date.required' => 'Tanggal pelatihan wajib diisi.',
            'schedule_date.date' => 'Tanggal pelatihan harus berupa tanggal yang valid.',
            'start_time.required' => 'Jam mulai wajib diisi.',
            'start_time.date_format' => 'Format jam mulai tidak valid.',
            'end_time.required' => 'Jam selesai wajib diisi.',
            'end_time.date_format' => 'Format jam selesai tidak valid.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
            'zoom_link.required' => 'Tautan Zoom wajib diisi.',
            'zoom_link.url' => 'Tautan Zoom harus berupa URL yang valid.',
            'zoom_link.max' => 'Tautan Zoom terlalu panjang.',
            'mentor_name.required' => 'Nama mentor wajib diisi.',
            'mentor_name.string' => 'Nama mentor harus berupa teks.',
            'mentor_name.max' => 'Nama mentor maksimal 255 karakter.',
            'mentor_role.required' => 'Jabatan mentor wajib diisi.',
            'mentor_role.string' => 'Jabatan mentor harus berupa teks.',
            'mentor_role.max' => 'Jabatan mentor maksimal 255 karakter.',
            'mentor_avatar.image' => 'Avatar mentor harus berupa gambar.',
            'mentor_avatar.mimes' => 'Avatar mentor harus berformat JPG, PNG, atau WEBP.',
            'mentor_avatar.max' => 'Ukuran avatar mentor maksimal 2 MB.',
            'duration.required' => 'Durasi pelatihan wajib diisi.',
            'duration.max' => 'Durasi maksimal 100 karakter.',
            'level.required' => 'Level pelatihan wajib diisi.',
            'level.max' => 'Level maksimal 100 karakter.',
            'description.required' => 'Deskripsi pelatihan wajib diisi.',
            'description.max' => 'Deskripsi pelatihan terlalu panjang.',
            'syllabus.required' => 'Silabus wajib diisi.',
            'syllabus.max' => 'Silabus terlalu panjang.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat JPG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
            'quota.integer' => 'Kuota harus berupa angka bulat.',
            'quota.min' => 'Kuota minimal 1 peserta.',
            'status.required' => 'Status pelatihan wajib dipilih.',
            'status.in' => 'Status pelatihan tidak valid.',
        ];
    }
}
