<?php

namespace App\Http\Requests\Plot;
 
use Illuminate\Foundation\Http\FormRequest;
 
class AddFertilizerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fertilizer_type' => ['required', 'string', 'in:organic,bone_meal,npk_balanced,seaweed,lime'],
        ];
    }
}
