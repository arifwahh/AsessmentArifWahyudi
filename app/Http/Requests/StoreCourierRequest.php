<?php

namespace App\Http\Requests;

use App\Models\Courier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:couriers,email'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9][0-9\s\-]{6,19}$/', 'unique:couriers,phone'],
            'level' => ['required', 'integer', 'between:'.Courier::LEVEL_MIN.','.Courier::LEVEL_MAX],
            'vehicle_type' => ['required', 'string', Rule::in(Courier::VEHICLE_TYPES)],
            'vehicle_plate' => ['required', 'string', 'max:15', 'regex:/^[A-Za-z]{1,2}\s?\d{1,4}\s?[A-Za-z]{0,3}$/', 'unique:couriers,vehicle_plate'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
