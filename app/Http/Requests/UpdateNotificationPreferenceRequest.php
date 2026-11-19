<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationPreferenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pengguna';
    }

    public function rules(): array
    {
        return [
            'is_notification_active' => ['required', 'boolean'],
        ];
    }
}
