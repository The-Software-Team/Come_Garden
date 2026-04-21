<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawSeedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'seed_type' => 'required|string',
            'quantity'  => 'required|integer|min:1'
        ];
    }
}