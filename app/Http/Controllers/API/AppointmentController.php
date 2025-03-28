<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\DoctorAvailability;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BookAppointmentRequest;

class AppointmentController extends Controller
{
    /**
     * Book an appointment with a doctor.
     *
     * @param BookAppointmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function book(BookAppointmentRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $doctor = User::find($validated['doctor_id']);
        if (!$doctor || !$doctor->isDoctor()) {
            return response()->json(['message' => 'Invalid doctor'], 400);
        }

        // Check if the slot is available
        $availability = DoctorAvailability::where('doctor_id', $validated['doctor_id'])
            ->where('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->first();

        if (!$availability) {
            return response()->json(['message' => 'This slot is not available'], 400);
        }

        // Check if the slot is already booked
        $isBooked = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('date', $validated['date'])
            ->where('time_slot', $validated['time_slot'])
            ->where('status', 'booked')
            ->exists();

        if ($isBooked) {
            return response()->json(['message' => 'This slot is already booked'], 400);
        }

        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $user->id,
            'date' => $validated['date'],
            'time_slot' => $validated['time_slot'],
            'status' => 'booked',
        ]);

        return response()->json([
            'message' => 'Appointment booked successfully',
            'appointment' => $appointment,
        ], 201);
    }

    /**
     * Get all appointments for a patient.
     *
     * @param int $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexPatient($patientId)
    {
        $user = Auth::user();

        if ($user->id != $patientId || !$user->isPatient()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointments = Appointment::where('patient_id', $patientId)
            ->with(['doctor' => function ($query) {
                $query->select('id', 'name');
            }])
            ->orderBy('date', 'asc')
            ->orderBy('time_slot', 'asc')
            ->get(['id', 'doctor_id', 'date', 'time_slot', 'status']);

        return response()->json([
            'patient_id' => $patientId,
            'appointments' => $appointments,
        ], 200);
    }
}
