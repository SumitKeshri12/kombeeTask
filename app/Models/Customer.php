<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city_id',
        'address'
    ];

    // Scope for active customers
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Custom validation rules
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string'
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
} 