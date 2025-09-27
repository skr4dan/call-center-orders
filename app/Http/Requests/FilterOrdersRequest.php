<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class FilterOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'status' => ['nullable', 'string', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Неверный статус.',
            'search.string' => 'Неверный формат поиска.',
            'search.max' => 'Максимальная длина поиска: :max символов.',
            'date_from.date_format' => 'Неверный формат даты.',
            'date_to.date_format' => 'Неверный формат даты.',
            'date_to.after_or_equal' => 'Дата по должна быть позже или совпадать с датой с.',
            'status.string' => 'Неверный формат статуса.',
        ];
    }

    public function attributes(): array
    {
        return [
            'search' => 'Поиск',
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
            'status' => 'Статус',
        ];
    }
}
