<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    protected $itemId;

    protected function prepareForValidation()
    {
        // Get the item ID from the route parameter
        // Laravel uses camelCase for route model binding (menuItem for menu-items)
        $item = $this->route('menuItem') ?: $this->route('menu_item');
        
        if ($item) {
            // If it's a model instance, get the ID
            if (is_object($item) && method_exists($item, 'getKey')) {
                $this->itemId = $item->getKey();
            } elseif (is_object($item) && isset($item->id)) {
                $this->itemId = $item->id;
            } else {
                $this->itemId = $item;
            }
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:menu_categories,id',
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:menu_items,slug,' . $this->itemId,
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'is_special' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'image_id' => 'nullable|exists:media,id',
            'order' => 'sometimes|integer|min:0',
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
                'message' => 'You do not have permission to update menu items.'
            ], 403)
        );
    }
}
