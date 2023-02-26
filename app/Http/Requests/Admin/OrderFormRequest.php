<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderFormRequest extends FormRequest
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
            'date' => ['required', 'string', 'max:10', 'regex:/[0-3][0-9][\.][0-1][0-9][\.][2][0][0-9][0-9]/'],
            'phone' => ['max:18', 'regex:/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            'email' => 'required|email|max:100',
            'address' => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'date' => 'Введите корректную дату',
            'phone' => 'Введите корректный номер телефона'
        ];
    }
}
