<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_]+$/',
                'max:50',
                'min:3',
                'unique:users,username',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'username.required' => 'Некорректные параметры запроса.',
            'username.unique'   => 'Пользователь с таким именем уже существует.',
            'username.min'      => 'Некорректные параметры запроса.',
            'username.max'      => 'Некорректные параметры запроса.',
            'username.regex'    => 'Некорректные параметры запроса.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $errorMessage = $validator->errors()->first('username');

        $statusCode = match ($errorMessage) {
            'Некорректные параметры запроса.' => 400,
            'Пользователь с таким именем уже существует.' => 409,
            default => 422,
        };

        throw new HttpResponseException(response()->json([
            'message' => $errorMessage,
        ], $statusCode));
    }
}
