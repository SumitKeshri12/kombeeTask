<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers.index');
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('customers.create', compact('cities'));
    }

    public function store(CustomerStoreRequest $request)
    {
        try {
            $customer = Customer::create($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully',
                    'customer' => $customer
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer created successfully');

        } catch (Exception $e) {
            \Log::error('Customer creation error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error creating customer: ' . $e->getMessage());
        }
    }

    public function edit(Customer $customer)
    {
        try {
            $cities = City::orderBy('name')->get();
            return view('customers.edit', compact('customer', 'cities'));
        } catch (Exception $e) {
            \Log::error('Error loading customer edit form: ' . $e->getMessage());
            return back()->with('error', 'Error loading customer: ' . $e->getMessage());
        }
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        try {
            $customer->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully',
                    'customer' => $customer
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer updated successfully');

        } catch (Exception $e) {
            \Log::error('Customer update error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error updating customer: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully'
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', 'Customer deleted successfully');

        } catch (Exception $e) {
            \Log::error('Customer deletion error: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting customer: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
    }

    public function getData()
    {
        try {
            $customers = Customer::with('city');

            return DataTables::of($customers)
                ->addColumn('city_name', function ($customer) {
                    return $customer->city ? $customer->city->name : 'N/A';
                })
                ->addColumn('actions', function ($customer) {
                    return view('customers.actions', compact('customer'))->render();
                })
                ->rawColumns(['actions'])
                ->toJson();

        } catch (Exception $e) {
            \Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading customers'], 500);
        }
    }
} 