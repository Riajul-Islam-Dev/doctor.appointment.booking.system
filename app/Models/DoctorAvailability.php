<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    use HasFactory;

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
    ];

    /**
     * Get the doctor that owns this availability.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the appointment booked for this availability (if any).
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class)
            ->where('doctor_id', $this->doctor_id)
            ->where('date', $this->date)
            ->where('time_slot', $this->time_slot);
    }
}
