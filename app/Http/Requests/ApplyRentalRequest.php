<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ApplyRentalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'plot_id' => 'required|exists:plots,id',
            'share' => 'required|numeric|min:0.1|max:1'
        ];
    }
}



