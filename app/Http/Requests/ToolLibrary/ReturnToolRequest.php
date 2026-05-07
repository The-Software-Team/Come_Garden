<?php

namespace App\Http\Requests\ToolLibrary;
use Illuminate\Foundation\Http\FormRequest;


class ReturnToolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => 'required',
            // 'cleaned'    => 'boolean' // QR code session later
        ];
    }
}