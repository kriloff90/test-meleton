<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConvertResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'currency_from' => $this->currency_from,
            'currency_to' => $this->currency_to,
            'value' => $this->decimalNotation($this->value),
            'converted_value' => $this->decimalNotation($this->converted_value),
            'rate' => $this->rate,
        ];
    }

    private function decimalNotation($float) {
        $parts = explode('E', $float);

        return count($parts) === 2
            ? rtrim(number_format($float, abs(end($parts)) + strlen($parts[0])), '.0')
            : $float;
    }
}
