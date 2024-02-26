<?php

namespace App\Http\Requests;

use App\Rules\HasLessThan2000Objects;
use App\Rules\IsActiveRef;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ProcessDemoTestRequest extends FormRequest
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
            '*' => new HasLessThan2000Objects(),
            '*.ref' => ['required', 'string', 'distinct', new IsActiveRef()],
            '*.name' => 'required|string',
            '*.description' => 'nullable|string',
        ];
    }

    /**
     * Returns one error message at a time.
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'message' => $validator->errors()->first(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }
}
