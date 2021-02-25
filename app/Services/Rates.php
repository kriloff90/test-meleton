<?php

namespace App\Services;

use Http;

use Illuminate\Support\Collection;

class Rates
{
    const URL = 'http://blockchain.info/ticker';

    public function getRates() : Collection
    {
        $rates = Http::get(self::URL);

        if (!$rates->successful()) {
            abort('Ошибка запроса валют.', 500);
        }

        return collect($rates->json());
    }
}
