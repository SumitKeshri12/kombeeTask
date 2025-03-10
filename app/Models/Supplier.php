<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes; // Add this if you want soft deletes

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city_id',
        'address'
    ];

    protected $guarded = ['id'];

    // Scope for active suppliers
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Custom validation rules
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string'
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
} 