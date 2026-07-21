<?php

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users in admin panel
    }

    protected $specialtyId;

    protected function prepareForValidation()
    {
        // Get the specialty ID from the route parameter
        $specialty = $this->route('specialty');
        
        if ($specialty) {
            // If it's a model instance, get the ID
            if (is_object($specialty) && method_exists($specialty, 'getKey')) {
                $this->specialtyId = $specialty->getKey();
            } elseif (is_object($specialty) && isset($specialty->id)) {
                $this->specialtyId = $specialty->id;
            } else {
                $this->specialtyId = $specialty;
            }
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('specialties', 'slug')->ignore($this->specialtyId)->whereNull('deleted_at'),
            ],
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image_id' => 'nullable|exists:media,id',
            'is_active' => 'sometimes|boolean',
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
                'message' => 'You do not have permission to update specialties.'
            ], 403)
        );
    }
}

