<?php

namespace App\Rules;

use App\Models\Convert;

use Illuminate\Contracts\Validation\Rule;

class CheckCurrency implements Rule
{
    private $from;

    public function __construct(string $from)
    {
        $this->from = $from;
    }

    public function passes($attribute, $value)
    {
        return preg_match(Convert::getRegex($this->from), $value) > 0;
    }

    public function message()
    {
        return 'Некорректное значение.';
    }
}
