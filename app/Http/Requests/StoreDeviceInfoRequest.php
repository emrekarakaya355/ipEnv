<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'nullable|string|max:255',
            'building' => 'nullable|string:max:255',
            'unit' => 'nullable|string:max:255',
            'update_reason' => 'nullable|string|max:255',
            'block' => 'nullable|string',
            'floor' => 'nullable|string',
            'room_number' => 'nullable|string',
            'ip_address' => 'nullable|ipv4',
        ];
    }

}
