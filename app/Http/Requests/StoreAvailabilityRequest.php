<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isDoctor(); // Only doctors can set availability
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availabilities' => 'required|array|min:1',
            'availabilities.*.date' => 'required|date|after_or_equal:today',
            'availabilities.*.time_slot' => 'required|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'availabilities.required' => 'At least one availability slot is required.',
            'availabilities.*.date.after_or_equal' => 'The date must be today or in the future.',
            'availabilities.*.time_slot.date_format' => 'The time slot must be in HH:MM format (e.g., 14:00).',
        ];
    }
}
