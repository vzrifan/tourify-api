<?php

namespace App\Http\Requests\Auth;

use App\Helpers\ResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            "name" => "required|string|max:120",
            "email" => "required|string",
            "password" => "required|string|min:8|confirmed",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ResponseFormatter::error(['errors' => $validator->messages()->toArray()], $validator->messages()->first());
        throw new ValidationException($validator, $response);
    }
}
