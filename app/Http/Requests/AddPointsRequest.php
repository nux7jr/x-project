<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddPointsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'points' => [
                'required',
                'integer',
                'max:10000',
                'min:1',
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'points.required' => 'Некорректные параметры запроса.',
            'points.integer' => 'Некорректные параметры запроса.',
            'points.min' => 'Некорректные параметры запроса.',
            'points.max' => 'Некорректные параметры запроса.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $errorMessage = $validator->errors()->first();

        throw new HttpResponseException(response()->json([
            'message' => $errorMessage,
        ], 400));
    }
}
