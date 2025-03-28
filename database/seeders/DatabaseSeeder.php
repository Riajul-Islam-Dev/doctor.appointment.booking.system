<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use App\Models\DoctorAvailability;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create specific users for testing
        $doctor1 = User::factory()->create([
            'name' => 'Dr. Alice Smith',
            'email' => 'alice@doctor.com',
            'role' => 'doctor',
        ]);

        $doctor2 = User::factory()->create([
            'name' => 'Dr. Bob Johnson',
            'email' => 'bob@doctor.com',
            'role' => 'doctor',
        ]);

        $patient1 = User::factory()->create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@patient.com',
            'role' => 'patient',
        ]);

        $patient2 = User::factory()->create([
            'name' => 'Dana White',
            'email' => 'dana@patient.com',
            'role' => 'patient',
        ]);

        // Create additional random doctors and patients
        User::factory()->doctor()->count(3)->create();
        User::factory()->patient()->count(5)->create();

        // Create availability slots for doctors
        DoctorAvailability::factory()->count(10)->create(['doctor_id' => $doctor1->id]);
        DoctorAvailability::factory()->count(8)->create(['doctor_id' => $doctor2->id]);

        // Create some booked appointments
        Appointment::factory()->create([
            'doctor_id' => $doctor1->id,
            'patient_id' => $patient1->id,
            'date' => '2025-04-01',
            'time_slot' => '14:00',
            'status' => 'booked',
        ]);

        Appointment::factory()->create([
            'doctor_id' => $doctor2->id,
            'patient_id' => $patient2->id,
            'date' => '2025-04-02',
            'time_slot' => '10:00',
            'status' => 'booked',
        ]);

        // Create additional random appointments
        Appointment::factory()->count(5)->create();
    }
}
