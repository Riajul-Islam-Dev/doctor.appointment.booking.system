<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the availabilities for the doctor.
     */
    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id');
    }

    /**
     * Get the appointments where the user is the doctor.
     */
    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get the appointments where the user is the patient.
     */
    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Check if the user is a doctor.
     */
    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if the user is a patient.
     */
    public function isPatient()
    {
        return $this->role === 'patient';
    }
}
