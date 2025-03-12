<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StateController extends Controller
{
    /**
     * Get cities for a specific state.
     *
     * @param State $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(State $state)
    {
        try {
            return response()->json([
                'cities' => $state->cities
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage(), [
                'exception' => $e,
                'state_id' => $state->id
            ]);
            
            return response()->json([
                'error' => 'Failed to fetch cities',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 