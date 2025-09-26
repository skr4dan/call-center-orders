<?php

namespace App\Http\Requests\Api;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fio' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'inn' => ['nullable', 'string', 'regex:/^\d{10}(\d{2})?$/'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
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
                        $fail('Количество должно быть больше 0, если указано название товара.');
                    }
                },
            ],
            'products.*.unit' => ['nullable', 'required_with:products.*.name', 'string', 'in:'.implode(',', array_keys(OrderProduct::UNIT_LABELS))],
        ];
    }
}
