<?php

namespace App\Http\Controllers\API\V1;

use Auth;

use App\Models\Convert;

use App\Http\Resources\ConvertResource;

use App\Http\Requests\Currency\ConvertRequest;

use App\Http\Controllers\Controller;

use App\Services\Rates;

class CurrencyController extends Controller
{
    private $ratesService;

    public function __construct(Rates $ratesService)
    {
        $this->ratesService = $ratesService;
    }

    public function index()
    {
        return ConvertResource::collection(Auth::user()->converts()->get());
    }

    public function rates()
    {
        return $this
            ->ratesService
            ->getRates()
            // If isset filter.currency then filter array from blockchain.info.
            ->when(!empty(request()->input('filter.currency', null)), function ($collection) {
                return $collection->filter(function ($item, $key) {
                    return $key === request()->input('filter.currency');
                });
            })
            // Prepare data
            ->map(function ($item, $key) {
                return [
                    'price' => Convert::addCommission($item['buy'], $key), // Add commission.
                    'symbol' => $item['symbol'],
                ];
            })
            ->sortBy('price');
    }

    public function convert(ConvertRequest $request)
    {
        return response(ConvertResource::make(Convert::create($request->validated())));
    }
}
