<?php

namespace App\Http\Requests\PublicApi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone') && $this->input('phone') !== null && $this->input('phone') !== '') {
            $this->merge([
                'phone' => preg_replace('/\s+/', '', (string) $this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        $ratingRule = 'required|integer|between:1,5';

        return [
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^(\+?251|0)?[79]\d{8}$/'],
            'visit_date' => 'required|date|before_or_equal:today',
            'customer_order' => 'nullable|string|max:2000',
            'food_taste' => $ratingRule,
            'food_presentation' => $ratingRule,
            'food_freshness' => $ratingRule,
            'food_portion_size' => $ratingRule,
            'service_friendliness' => $ratingRule,
            'service_speed' => $ratingRule,
            'service_accuracy' => $ratingRule,
            'service_attentiveness' => $ratingRule,
            'environment_cleanliness' => $ratingRule,
            'environment_ambiance' => $ratingRule,
            'environment_comfort' => $ratingRule,
            'comments' => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be a valid Ethiopian mobile number.',
            '*.between' => 'Each rating must be between 1 and 5.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
