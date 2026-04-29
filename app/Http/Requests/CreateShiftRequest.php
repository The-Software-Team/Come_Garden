<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class CreateShiftRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'days'       => 'required|integer|min:1'
        ];
    }
}