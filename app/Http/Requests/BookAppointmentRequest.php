<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BookAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isPatient(); // Only patients can book appointments
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|date_format:H:i',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'date.after_or_equal' => 'The date must be today or in the future.',
            'time_slot.date_format' => 'The time slot must be in HH:MM format (e.g., 14:00).',
        ];
    }
}
