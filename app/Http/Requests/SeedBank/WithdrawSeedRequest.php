<?php

namespace App\Http\Requests\SeedBank;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawSeedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'seed_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ];
    }
}