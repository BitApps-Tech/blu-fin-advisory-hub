<?php

namespace App\Http\Requests\MenuCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMenuCategoryRequest extends FormRequest
{
    protected $categoryId;

    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    protected function prepareForValidation()
    {
        // Get the category ID from the route parameter
        $category = $this->route('menuCategory');
        
        if ($category) {
            // If it's a model instance, get the ID
            if (is_object($category) && method_exists($category, 'getKey')) {
                $this->categoryId = $category->getKey();
            } else {
                $this->categoryId = $category;
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:menu_categories,slug,' . $this->categoryId,
            'description' => 'nullable|string',
            'is_active' => 'sometimes|in:0,1,true,false',
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
                'message' => 'You do not have permission to update menu categories.'
            ], 403)
        );
    }
}

