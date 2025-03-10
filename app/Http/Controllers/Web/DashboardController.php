<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $stats = [
                'users_count' => User::count(),
                'customers_count' => Customer::count(),
                'suppliers_count' => Supplier::count(),
            ];

            return view('dashboard', compact('stats'));
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('dashboard')->with('error', 'Error loading dashboard data');
        }
    }
} 