<?php

namespace App\Http\Requests;

use App\Rules\IsActiveRef;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'required',
            '*.ref' => ['required', 'string', 'distinct', new IsActiveRef()],
            '*.name' => 'required|string',
            '*.description' => 'nullable|string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            if (count($data) > 2000) {
                $validator->errors()->add('request', 'The request may not contain more than 2000 objects.');
            }
        });
    }
}
