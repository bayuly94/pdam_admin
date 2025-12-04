<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolumeHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'volume' => 'required|numeric|min:0',
            'date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'after.gte' => 'The after value must be greater than or equal to the before value.',
        ];
    }
}