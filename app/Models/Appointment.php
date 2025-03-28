<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d', // Cast date to Y-m-d format
        'time_slot' => 'string', // Time slot as string (e.g., "14:00")
        'status' => 'string', // Status as string (e.g., "booked")
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the doctor for this appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the patient for this appointment.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the availability slot this appointment corresponds to (if any).
     */
    public function availability()
    {
        return $this->hasOne(DoctorAvailability::class)
            ->where('doctor_id', $this->doctor_id)
            ->where('date', $this->date)
            ->where('time_slot', $this->time_slot);
    }
}
