<?php

namespace App\Http\Requests\Admin;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class ApiOrderUpdate extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'string', 'min:10', 'max:10', 'regex:/[0-3][0-9][\.][0-1][0-9][\.][2][0][0-9][0-9]/'],
            'phone' => ['max:18', 'regex:/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            'email' => 'required|email',
            'address' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'date' => 'Введите корректную дату',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $response = response()
            ->json([ 'result' => false, 'errors' => $validator->errors()], 422);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag);
    }
}
