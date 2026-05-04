<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class BookToolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tool_name'   => 'required|string',
            'duration_hours'     => 'required|integer|min:1'
        ];
    }
}