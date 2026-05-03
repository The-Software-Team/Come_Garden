<?php

namespace App\Http\Requests\Plot;

use Illuminate\Foundation\Http\FormRequest;

class ReportInfectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|string|max:100',
        ];
    }
}