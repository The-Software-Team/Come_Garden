<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // or role check if needed
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'usage_status' => 'nullable|string|in:low,medium,high',
            'maintenance_threshold_hours' => 'nullable|integer|min:1',
        ];
    }
}