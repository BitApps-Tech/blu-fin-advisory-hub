<?php

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            // Ignore soft-deleted specialties; the controller restores those rows.
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('specialties', 'slug')->whereNull('deleted_at')],
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image_id' => 'nullable|exists:media,id',
            'is_active' => 'boolean',
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
                'message' => 'You do not have permission to create specialties.'
            ], 403)
        );
    }
}

