<?php

namespace App\Http\Requests\Order;

use App\Rules\EthiopianPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow if user is authenticated (admin panel)
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/\s+/', '', (string) $this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', new EthiopianPhone()],
            'email' => 'nullable|email|max:255',
            'order_type' => 'required|in:pickup,delivery,dinein',
            'scheduled_at' => 'nullable|date',
            'note' => 'nullable|string',
            'status' => 'nullable|in:pending,confirmed,preparing,ready,completed,cancelled',
            'total' => 'nullable|numeric|min:0',
            'items' => 'nullable|array|min:1',
            'items.*.menu_item_id' => 'nullable|exists:menu_items,id',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.qty' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'You do not have permission to create orders.'
            ], 403)
        );
    }
}

