<?php

namespace App\Rules;

use App\Models\Convert;

use Illuminate\Contracts\Validation\Rule;

class CheckMinValue implements Rule
{
    private $from;

    public function __construct(string $from)
    {
        $this->from = $from;
    }

    public function passes($attribute, $value)
    {
        return Convert::getMinValue($this->from) <= (float) $value;
    }

    public function message()
    {
        return 'Слишком маленькое значение.';
    }
}
