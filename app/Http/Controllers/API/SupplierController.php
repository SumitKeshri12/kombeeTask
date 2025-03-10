<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-suppliers')->only('index', 'show');
        $this->middleware('permission:create-suppliers')->only('store');
        $this->middleware('permission:edit-suppliers')->only('update');
        $this->middleware('permission:delete-suppliers')->only('destroy');
    }

    public function index()
    {
        $suppliers = Supplier::paginate(10);
        return response()->json($suppliers);
    }

    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());
        return response()->json($supplier, 201);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted successfully']);
    }
} 