<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'postcode',
        'gender',
        'hobbies',
        'password',
        'city_id',
        'role',
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
        'hobbies' => 'array'
    ];

    // Default role when creating a new user
    protected $attributes = [
        'role' => 'User',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Scope to exclude logged-in user
    public function scopeExcludeAuth($query)
    {
        return $query->where('id', '!=', auth()->id());
    }

    public static function getGenderOptions(): array
    {
        return [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        ];
    }

    public static function getHobbyOptions(): array
    {
        return [
            'reading' => 'Reading',
            'sports' => 'Sports',
            'music' => 'Music',
            'travel' => 'Travel',
            'cooking' => 'Cooking',
            'gaming' => 'Gaming',
            'art' => 'Art',
            'photography' => 'Photography'
        ];
    }

    public function isSuperAdmin()
    {
        return $this->role === 'Super Admin';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    public function isUser()
    {
        return $this->role === 'User';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        $roles = is_array($roles) ? $roles : explode(',', $roles);
        return in_array($this->role, array_map('trim', $roles));
    }
}
