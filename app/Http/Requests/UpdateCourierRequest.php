<?php

namespace App\Http\Requests;

use App\Models\Courier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourierRequest extends FormRequest
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
        $courier = $this->route('courier');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('couriers', 'email')->ignore($courier)],
            'phone' => ['sometimes', 'required', 'string', 'max:20', 'regex:/^\+?[0-9][0-9\s\-]{6,19}$/', Rule::unique('couriers', 'phone')->ignore($courier)],
            'level' => ['sometimes', 'required', 'integer', 'between:'.Courier::LEVEL_MIN.','.Courier::LEVEL_MAX],
            'vehicle_type' => ['sometimes', 'required', 'string', Rule::in(Courier::VEHICLE_TYPES)],
            'vehicle_plate' => ['sometimes', 'required', 'string', 'max:15', 'regex:/^[A-Za-z]{1,2}\s?\d{1,4}\s?[A-Za-z]{0,3}$/', Rule::unique('couriers', 'vehicle_plate')->ignore($courier)],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
