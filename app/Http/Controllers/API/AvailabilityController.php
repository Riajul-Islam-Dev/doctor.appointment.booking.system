<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DoctorAvailability;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAvailabilityRequest;

class AvailabilityController extends Controller
{
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
            // Check if slot already exists to avoid duplicates
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

        $availabilities = DoctorAvailability::where('doctor_id', $id)
            ->whereDoesntHave('appointment', function ($query) {
                $query->where('status', 'booked');
            })
            ->get(['id', 'date', 'time_slot']);

        return response()->json([
            'doctor_id' => $id,
            'availabilities' => $availabilities,
        ], 200);
    }
}
