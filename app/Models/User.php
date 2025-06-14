<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }


    // Tambahkan relasi profile di model User
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // Tambahkan method untuk membuat profile jika belum ada
    public function getProfileAttribute()
    {
        if (!$this->relationLoaded('profile')) {
            $this->load('profile');
        }

        if (!$this->profile) {
            $this->profile()->create();
            $this->load('profile');
        }

        return $this->getRelation('profile');
    }
}
