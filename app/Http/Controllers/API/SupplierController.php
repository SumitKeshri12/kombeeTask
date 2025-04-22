<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Http\Requests\SupplierStoreRequest;
use App\Models\Supplier;
use App\Traits\ApiResponse;
use App\Traits\GeneratesIdempotencyKey;
use Exception;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    use ApiResponse, GeneratesIdempotencyKey;

    public function __construct()
    {
        Log::info('SupplierController middleware setup starting');
        
        $this->middleware('auth:api');
        Log::info('Added auth:api middleware');
        
        $this->middleware('permission:view-suppliers')->only(['index', 'show']);
        $this->middleware('permission:create-suppliers')->only('store');
        $this->middleware('permission:edit-suppliers')->only('update');
        $this->middleware('permission:delete-suppliers')->only('destroy');
        Log::info('Added permission middlewares');
        
        $this->middleware('idempotent')->only(['store', 'update', 'destroy']);
        Log::info('Added idempotent middleware');
        
        Log::info('SupplierController middleware setup complete');
    }

    public function index()
    {
        try {
            $suppliers = Supplier::paginate(10);
            return $this->successResponse($suppliers, 'Suppliers fetched successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to fetch suppliers', 500);
        }
    }

    public function store(SupplierStoreRequest $request)
    {
        try {
            Log::info('Store method called', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'user_roles' => auth()->user()->roles->pluck('name'),
                'user_permissions' => auth()->user()->permissions->pluck('name'),
                'request_data' => $request->except(['password'])
            ]);
            
            $supplier = Supplier::create($request->validated());
            
            Log::info('Supplier created successfully', [
                'supplier_id' => $supplier->id,
                'supplier_data' => $supplier->toArray()
            ]);
            
            return $this->successResponse($supplier, 'Supplier created successfully', 201);
        } catch (Exception $e) {
            Log::error('Failed to create supplier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['password'])
            ]);
            return $this->errorResponse('Failed to create supplier: ' . $e->getMessage(), 500);
        }
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        try {
            $supplier->update($request->validated());
            return $this->successResponse($supplier, 'Supplier updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update supplier', 500);
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return $this->successResponse(null, 'Supplier deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete supplier', 500);
        }
    }
} 