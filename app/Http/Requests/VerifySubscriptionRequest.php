<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifySubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $subscription = $this->route('subscription');

        return $subscription instanceof Subscription
            && ($this->user()?->can('verify', $subscription) ?? false);
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['confirm', 'reject'])],
            'rejection_reason' => ['nullable', 'required_if:action,reject', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Tindakan verifikasi wajib dipilih.',
            'action.in' => 'Tindakan verifikasi tidak valid.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.string' => 'Alasan penolakan harus berupa teks.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 5000 karakter.',
        ];
    }
}
