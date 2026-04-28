<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class BookToolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'tool_id'   => 'required|exists:tools,id',
            'hours'     => 'required|integer|min:1'
        ];
    }
}