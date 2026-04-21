<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class CreateShiftRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'admin_id'   => 'required|exists:members,id',
            'start_date' => 'required|date',
            'days'       => 'required|integer|min:1'
        ];
    }
}