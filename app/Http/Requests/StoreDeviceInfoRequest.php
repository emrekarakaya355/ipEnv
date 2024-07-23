<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ip_address' => 'nullable|ipv4',
            'description' => 'nullable|string|max:255',
            'location_id' => 'nullable|exists:locations,id',
            'update_reason' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:3',
            'floor' => 'nullable|string|max:10',
            'room_number' => 'nullable|integer|max:255',
        ];
    }
}
