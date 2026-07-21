<?php

namespace App\Http\Requests\GalleryItem;

use App\Models\GalleryItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateGalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'caption' => 'nullable|string',
            'category' => 'sometimes|in:' . implode(',', GalleryItem::CATEGORIES),
            'image_id' => 'sometimes|exists:media,id',
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
                'message' => 'You do not have permission to update gallery items.'
            ], 403)
        );
    }
}

