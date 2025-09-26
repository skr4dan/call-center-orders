<?php

namespace App\Http\Requests;

use App\Models\OrderProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role->isOperator();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fio' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'inn' => ['nullable', 'string', 'regex:/^\d{10}(\d{2})?$/'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'products' => ['array'],
            'products.*.name' => ['nullable', 'required_unless:products.*.quantity,0', 'string', 'max:255'],
            'products.*.quantity' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $index = Str::between($attribute, 'products.', '.quantity');
                    $name = $this->input("products.{$index}.name", null);
                    if (filled($name) && (! is_numeric($value) || $value <= 0)) {
                        $fail('Кол-во должно быть больше 0, если указано название товара.');
                    }
                },
            ],
            'products.*.unit' => ['nullable', 'required_with:products.*.name', 'string', 'in:'.implode(',', array_keys(OrderProduct::UNIT_LABELS))],
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'Необходимо добавить хотя бы один товар.',
            'products.array' => 'Неверный формат данных товаров.',
            'products.*.unit.required_with' => 'Должно быть указано, если указано название товара.',
            'products.*.name.required_unless' => 'Необходимо указать название товара, если указано количество.',

            '*.required' => 'Обязательно для заполнения.',
            '*.string' => 'Должно быть текстом.',
            '*.max' => 'Не должно превышать :max символов.',
            '*.email' => 'Неверный формат электронной почты.',
            '*.integer' => 'Должно быть целым числом.',
            '*.min' => 'Минимальное значение: :min.',
            '*.in' => 'Неверное значение.',

            'inn.regex' => 'Неверный формат ИНН.',
        ];
    }

    public function attributes(): array
    {
        return [
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Почта',
            'inn' => 'ИНН',
            'company' => 'Название компании',
            'address' => 'Адрес',
            'products' => 'Товары',
            'products.*.name' => 'Название товара',
            'products.*.quantity' => 'Кол-во',
            'products.*.unit' => 'Единица измерения',
        ];
    }
}
