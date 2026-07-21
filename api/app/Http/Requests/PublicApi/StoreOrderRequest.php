<?php

namespace App\Http\Requests\PublicApi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    private const DELIVERY_MINIMUM_PRODUCTS = 4;
    public function authorize(): bool
    {
        return true; // Public endpoint
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
            'customer_name' => 'required|string|min:2|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\+?251|0)?[79]\d{8}$/'],
            'email' => 'nullable|email|max:255',
            'order_type' => 'required|in:pickup,delivery,dinein',
            'scheduled_at' => 'nullable|date|after:now',
            'note' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'nullable|exists:menu_items,id',
            'items.*.name' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be a valid Ethiopian mobile number (e.g. +251912345678 or 0912345678).',
            'customer_name.min' => 'Please enter your full name.',
            'items.min_delivery' => 'Delivery requires a minimum of ' . self::DELIVERY_MINIMUM_PRODUCTS . ' products.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('order_type') !== 'delivery') {
                return;
            }

            $totalQty = collect($this->input('items', []))->sum(fn ($item) => (int) ($item['qty'] ?? 0));

            if ($totalQty < self::DELIVERY_MINIMUM_PRODUCTS) {
                $validator->errors()->add(
                    'items',
                    'Delivery requires a minimum of ' . self::DELIVERY_MINIMUM_PRODUCTS . ' products.'
                );
            }
        });
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
}


