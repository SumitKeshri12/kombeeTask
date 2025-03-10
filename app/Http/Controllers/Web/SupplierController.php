<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('suppliers.create', compact('cities'));
    }

    public function store(SupplierStoreRequest $request)
    {
        try {
            $supplier = Supplier::create($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier created successfully',
                    'supplier' => $supplier
                ]);
            }

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier created successfully');

        } catch (Exception $e) {
            \Log::error('Supplier creation error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating supplier: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error creating supplier: ' . $e->getMessage());
        }
    }

    public function edit(Supplier $supplier)
    {
        try {
            $cities = City::orderBy('name')->get();
            return view('suppliers.edit', compact('supplier', 'cities'));
        } catch (Exception $e) {
            \Log::error('Error loading supplier edit form: ' . $e->getMessage());
            return back()->with('error', 'Error loading supplier: ' . $e->getMessage());
        }
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        try {
            $supplier->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier updated successfully',
                    'supplier' => $supplier
                ]);
            }

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier updated successfully');

        } catch (Exception $e) {
            \Log::error('Supplier update error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating supplier: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Error updating supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier deleted successfully'
                ]);
            }

            return redirect()->route('suppliers.index')
                ->with('success', 'Supplier deleted successfully');

        } catch (Exception $e) {
            \Log::error('Supplier deletion error: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting supplier: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }

    public function getData()
    {
        try {
            $suppliers = Supplier::with('city');

            return DataTables::of($suppliers)
                ->addColumn('city_name', function ($supplier) {
                    return $supplier->city ? $supplier->city->name : 'N/A';
                })
                ->addColumn('actions', function ($supplier) {
                    return view('suppliers.actions', compact('supplier'))->render();
                })
                ->rawColumns(['actions'])
                ->toJson();

        } catch (Exception $e) {
            \Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading suppliers'], 500);
        }
    }
} 