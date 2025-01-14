<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PeriodType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PeriodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => [
                'nullable',
                'string',
                'in:' . implode(',', array_column(PeriodType::cases(), 'value')),
            ],
        ];
    }

    /**
     * Получить период как enum.
     */
    public function getPeriodType(): PeriodType
    {
        $period = $this->input('period', PeriodType::default()->value);
        return PeriodType::from($period);
    }
 /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'period.in' => 'Некорректные параметры запроса.',
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
