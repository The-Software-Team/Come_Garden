<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class CreateListingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'item'      => 'required|string',
            'quantity'  => 'required|integer|min:1',
            'type'      => 'required|string',
            'request'   => 'nullable|string'
        ];
    }
}