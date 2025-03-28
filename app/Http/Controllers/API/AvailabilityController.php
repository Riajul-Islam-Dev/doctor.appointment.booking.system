<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DoctorAvailability;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAvailabilityRequest;

class AvailabilityController extends Controller
{
    /**
     * Get a list of all doctors.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $doctors = User::where('role', 'doctor')
            ->select('id', 'name')
            ->get();

        return response()->json([
            'doctors' => $doctors,
        ], 200);
    }

    /**
     * Store doctor's availability slots.
     *
     * @param StoreAvailabilityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAvailabilityRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        foreach ($validated['availabilities'] as $availability) {
            $exists = DoctorAvailability::where('doctor_id', $user->id)
                ->where('date', $availability['date'])
                ->where('time_slot', $availability['time_slot'])
                ->exists();

            if (!$exists) {
                DoctorAvailability::create([
                    'doctor_id' => $user->id,
                    'date' => $availability['date'],
                    'time_slot' => $availability['time_slot'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Availability slots set successfully',
        ], 201);
    }

    /**
     * Get available slots for a specific doctor.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $doctor = User::find($id);

        if (!$doctor || !$doctor->isDoctor()) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Get available slots by excluding booked appointments
        $availabilities = DoctorAvailability::where('doctor_id', $id)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('appointments')
                    ->whereColumn('appointments.doctor_id', 'doctor_availabilities.doctor_id')
                    ->whereColumn('appointments.date', 'doctor_availabilities.date')
                    ->whereColumn('appointments.time_slot', 'doctor_availabilities.time_slot')
                    ->where('appointments.status', 'booked')
                    ->whereNull('appointments.deleted_at');
            })
            ->get(['id', 'date', 'time_slot']);

        return response()->json([
            'doctor_id' => $id,
            'availabilities' => $availabilities,
        ], 200);
    }
}
