<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DoctorAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $availability = DoctorAvailability::factory()->create(); // Creates an availability slot

        return [
            'doctor_id' => $availability->doctor_id,
            'patient_id' => User::factory()->patient(), // Links to a patient
            'date' => $availability->date,
            'time_slot' => $availability->time_slot,
            'status' => 'booked',
        ];
    }
}
