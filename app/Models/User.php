<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneNumber',
        'fourDegits',
        'checkhash',
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
    ];
    public function roles()
    {
        return $this
            ->belongsToMany('App\Models\Role')
            ->withTimestamps();
    }
    public function authorizeRoles($roles)
    {
    if ($this->hasAnyRole($roles)) {
        return true;
    }
    abort(401, 'This action is unauthorized.');
    }
    public function hasAnyRole($roles)
    {
    if (is_array($roles)) {
        foreach ($roles as $role) {
        if ($this->hasRole($role)) {
            return true;
        }
        }
    } else {
        if ($this->hasRole($roles)) {
        return true;
        }
    }
    return false;
    }
    public function hasRole($role)
    {
    if ($this->roles()->where('name', $role)->first()) {
        return true;
    }
    return false;
    }
}
