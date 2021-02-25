<?php

namespace App\Http\Requests\Currency;

use App\Rules\CheckCurrency;
use App\Rules\CheckMinValue;

use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'currency_from' => ['required', 'string'],
            'currency_to' => ['required', 'string'],
            'value' => ['required', new CheckCurrency($this->input('currency_from')), new CheckMinValue($this->input('currency_from'))],
        ];
    }
}
