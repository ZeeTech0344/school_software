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
    protected $fillable = [
        'id',
        'employee_number',
        'employee_name',
        'name',
        'employee_post',
        'email',
        'user_type',
        'email_verified_at',
        'password',
        'phone_no',
        'father_cnic',
        'father_phon_no',
        'basic_sallary',
        'image',
        'employee_status',
        'account_for',
        'joining',
        'leaving',
        'operator',
    ];

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

    public function salary()
    {
        return $this->hasMany(salary::class, "employee_id", "id");
    }

    public function locker()
    {
        return $this->hasMany(LockerPaidAmount::class, "employee_id", "id");
    }

    public function easypaisa()
    {
        return $this->hasMany(EasypaisaPaidAmount::class, "employee_id", "id");
    }

    public function pendings()
    {
        return $this->hasMany(pendings::class, "employee_id", "id");
    }

    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, "employee_id", "id");
    }
}
