<?php

namespace App\Http\Requests\ToolLibrary;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'usage_status' => 'nullable|string|in:low,medium,high',
            'maintenance_threshold_hours' => 'nullable|integer|min:1',
        ];
    }
}