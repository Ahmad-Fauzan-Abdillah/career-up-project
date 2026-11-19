<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pengguna';
    }

    public function rules(): array
    {
        return [
            'training_id' => [
                'required',
                Rule::exists('trainings', 'id')->where('category', 'Premium'),
            ],
            'plan_type' => ['required', 'in:bulanan,tahunan'],
        ];
    }

    public function messages(): array
    {
        return [
            'training_id.required' => 'Pelatihan wajib dipilih.',
            'training_id.exists' => 'Pelatihan premium tidak ditemukan.',
            'plan_type.required' => 'Paket langganan wajib dipilih.',
            'plan_type.in' => 'Paket langganan tidak valid.',
        ];
    }
}
