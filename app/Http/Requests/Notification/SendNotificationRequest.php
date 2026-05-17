<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channel' => [
                'required',
                'in:email,sms',
            ],

            'message' => [
                'required',
                'string',
                'max:1000',
            ],

            'priority' => [
                'required',
                'in:high,normal',
            ],

            'recipients' => [
                'required',
                'array',
                'min:1',
            ],

            'recipients.*' => [
                'required',
                'string',
                'max:255',
            ],

            'idempotency_key' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
