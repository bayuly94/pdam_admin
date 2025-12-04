<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get a paginated list of customers
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        
        $customers = Customer::when($search, function($query) use ($search) {
                return $query->where('code', 'like', "%{$search}%")
                           ->orWhere('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'pagination' => [
                'total' => $customers->total(),
                'per_page' => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'from' => $customers->firstItem(),
                'to' => $customers->lastItem()
            ]
        ]);
    }

    /**
     * Search for a customer by code
     * 
     * @param string $code
     * @return JsonResponse
     */
    public function searchByCode(string $code): JsonResponse
    {
        $customer = Customer::where('code', $code)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $customer->load('volumes')
        ]);
    }
}
