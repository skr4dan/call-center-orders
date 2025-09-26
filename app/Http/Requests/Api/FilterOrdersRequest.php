<?php

namespace App\Http\Requests\Api;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class FilterOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d'],
            'status' => ['nullable', 'string', 'in:'.implode(',', array_keys(Order::STATUS_LABELS))],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
