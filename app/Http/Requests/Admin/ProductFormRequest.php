<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
            'price' => 'required|numeric|min:0.01|max:999999.99|regex:/^\d+(\.\d\d)?$/',
        ];
    }

    public function messages()
    {
        return [
            'title.min' => 'Не менее 3 символов',
            'price.numeric' => 'Введите корректную цену. Рубли копейки разделять точкой',
            'price.regex' => 'Строго 2 знака после точки'
        ];
    }


}
