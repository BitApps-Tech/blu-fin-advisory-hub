<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:menu_items,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_special' => 'boolean',
            'is_active' => 'boolean',
            'image_id' => 'nullable|exists:media,id',
            'order' => 'integer|min:0',
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
                'message' => 'You do not have permission to create menu items.'
            ], 403)
        );
    }
}
