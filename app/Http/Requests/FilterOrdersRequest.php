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
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d'],
            'status' => ['nullable', 'string', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Неверный статус.',
            'date_from.date' => 'Неверный формат даты.',
            'date_to.date' => 'Неверный формат даты.',
            'search.string' => 'Неверный формат поиска.',
            'search.max' => 'Максимальная длина поиска: :max символов.',
            'date_from.date' => 'Неверный формат даты.',
            'date_to.date' => 'Неверный формат даты.',
            'status.string' => 'Неверный формат статуса.',
            'status.in' => 'Неверный статус.',
            'date_from.date' => 'Неверный формат даты.',
            'date_to.date' => 'Неверный формат даты.',
            'status.string' => 'Неверный формат статуса.',
            'status.in' => 'Неверный статус.',
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
