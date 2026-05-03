<?php

namespace App\Http\Requests\SeedBank;

use Illuminate\Foundation\Http\FormRequest;

class DepositSeedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'owner_type' => 'required|string|max:100',
            'seed_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'viability' => 'required|integer|min:0|max:100',
            'origin' => 'nullable|string|max:255',
            'age' => 'required|integer|min:0',
        ];
    }
}