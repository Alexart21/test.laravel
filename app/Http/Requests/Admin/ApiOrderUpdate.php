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
//           'date' => 'required|string|max:10|regex:/(0?[1-9]|[12][0-9]|3[01]).(0?[1-9]|1[012]).(20\d\d)/',
            'date' => 'required|string|max:10|',
            'phone' => 'required',
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
