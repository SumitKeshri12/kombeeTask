<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // Scope to get states with cities
    public function scopeWithCities($query)
    {
        return $query->has('cities');
    }
} 