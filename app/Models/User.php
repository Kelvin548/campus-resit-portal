<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'student_id_number',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role checking helper methods.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isChiefExaminer(): bool
    {
        return $this->role === 'chief_examiner';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Student Registrations.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'user_id');
    }

    /**
     * Student Payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    /**
     * Registrations approved by Chief Examiner.
     */
    public function approvedRegistrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'approved_by');
    }

    /**
     * Timetables published by Chief Examiner.
     */
    public function publishedTimetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'published_by');
    }

    /**
     * Results recorded by Examiner/Admin.
     */
    public function enteredResults(): HasMany
    {
        return $this->hasMany(Result::class, 'entered_by');
    }
}
