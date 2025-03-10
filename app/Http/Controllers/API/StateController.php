<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        return response()->json(State::all());
    }

    public function cities(State $state)
    {
        return response()->json($state->cities);
    }
} 