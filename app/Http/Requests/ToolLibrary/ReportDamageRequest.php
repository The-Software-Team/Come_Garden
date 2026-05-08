<?php

namespace App\Http\Requests\ToolLibrary;

use Illuminate\Foundation\Http\FormRequest;


class ReportDamageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'severity'    => 'required|string|max:100',
            'reason' => 'required'
        ];
    }
}