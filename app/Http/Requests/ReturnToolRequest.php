<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class ReturnToolRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,id',
            'cleaned'    => 'boolean'
        ];
    }
}