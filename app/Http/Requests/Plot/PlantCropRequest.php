<?php

namespace App\Http\Requests\Plot;

use Illuminate\Foundation\Http\FormRequest;

class PlantCropRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|string|max:100',
        ];
    }
}