<?php

namespace App\Http\Requests\Order;

use App\Rules\EthiopianPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOrderRequest extends FormRequest
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
            'status' => 'sometimes|in:pending,confirmed,preparing,ready,completed,cancelled',
            'scheduled_at' => 'nullable|date',
            'note' => 'nullable|string',
            'customer_name' => 'sometimes|string|max:255',
            'phone' => ['sometimes', 'string', 'max:20', new EthiopianPhone()],
            'email' => 'nullable|email|max:255',
            'order_type' => 'sometimes|in:pickup,delivery,dinein',
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
                'message' => 'You do not have permission to update orders.'
            ], 403)
        );
    }
}
