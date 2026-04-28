<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class DepositSeedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'seed_type' => 'required|string',
            'quantity'  => 'required|integer|min:1',
            'viability' => 'required|integer|min:0|max:100',
            'origin'    => 'nullable|string',
            'age'       => 'nullable|integer'
        ];
    }
}