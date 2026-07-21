<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Concerns\ImportsCsv;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Rules\EthiopianPhone;
use App\Services\CustomerPhoneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use ImportsCsv;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Customer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => ['required', 'string', new EthiopianPhone()],
        ]);

        $phoneService = app(CustomerPhoneService::class);
        $normalizedPhone = $phoneService->normalize($validated['phone']);

        if (Customer::where('phone', $normalizedPhone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => ['phone' => ['This phone number is already registered.']],
            ], 422);
        }

        $customer = Customer::create([
            'name' => $validated['name'] ?? null,
            'phone' => $normalizedPhone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => ['required', 'string', new EthiopianPhone()],
        ]);

        $phoneService = app(CustomerPhoneService::class);
        $normalizedPhone = $phoneService->normalize($validated['phone']);

        if (Customer::where('phone', $normalizedPhone)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => ['phone' => ['This phone number is already registered.']],
            ], 422);
        }

        $customer->update([
            'name' => $validated['name'] ?? null,
            'phone' => $normalizedPhone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete(); // Soft delete with cascade

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.',
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $rows = $this->parseCsvUpload($request);
        $imported = 0;
        $errors = [];

        $phoneService = app(CustomerPhoneService::class);

        foreach ($rows as $index => $row) {
            $line = $index + 2;

            try {
                if (empty($row['phone'])) {
                    throw new \InvalidArgumentException('Phone is required.');
                }

                if (!$phoneService->isValid($row['phone'])) {
                    throw new \InvalidArgumentException('Phone must be a valid Ethiopian mobile number.');
                }

                $normalizedPhone = $phoneService->normalize($row['phone']);

                if (Customer::where('phone', $normalizedPhone)->exists()) {
                    throw new \InvalidArgumentException("Phone \"{$row['phone']}\" already exists.");
                }

                Customer::create([
                    'name' => $row['name'] ?? null,
                    'phone' => $normalizedPhone,
                ]);

                $imported++;
            } catch (\Throwable $e) {
                $errors[] = ['row' => $line, 'message' => $e->getMessage()];
            }
        }

        return $this->importResult($imported, $errors);
    }
}

