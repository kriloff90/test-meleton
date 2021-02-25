<?php

namespace App\Models;

use Auth;

use App\Services\Rates;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Convert extends Model
{
    use HasFactory;

    const BTC_NAME = 'BTC';

    const FEE = .02;

    const MIN_BTC_VALUE = .0000005556;
    const MIN_VALUE = .01;

    const REGEX_BTC = '/^0*(1?[0-9]|20)?[0-9]{0,6}(\.[0-9]{0,10}0*)?$/';
    const REGEX_OTHER = '/^(0|[1-9][0-9]*)?(\.\d{1,2})?$/';

    protected $fillable = [
        'currency_from',
        'currency_to',
        'value',
    ];

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
        'converted_value' => 'float',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            // Sell or buy BTC.
            $isBuyBtc = !self::isBtc($model->currency_from);

            // Get current currency.
            $currency = $isBuyBtc ? $model->currency_to : $model->currency_from;

            // Get price with commission.
            $price = self::addCommission(self::getCurrentPrice($isBuyBtc, $model), $currency);

            // Set attributes.
            $model->converted_value = self::roundValue(self::convertValue($isBuyBtc, $model->value, $price), $currency);
            $model->rate = $price;
            $model->user_id = Auth::user()->id;
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get min value by currency.
     *
     * @param string $currency
     * @return float
     */
    public static function getMinValue(string $currency) : float
    {
        return self::isBtc($currency) === self::BTC_NAME ? self::MIN_BTC_VALUE : self::MIN_VALUE;
    }

    /**
     * Get regex by currency.
     *
     * @param string $currency
     * @return string
     */
    public static function getRegex(string $currency) : string
    {
        return self::isBtc($currency) ? self::REGEX_BTC : self::REGEX_OTHER;
    }

    /**
     * Add commission by currency.
     *
     * @param float $value
     * @param string $currency
     * @return float
     */
    public static function addCommission(float $value, string $currency) : float
    {
        return self::roundValue($value * Convert::FEE + $value, $currency);
    }

    /**
     * Convert value to or from btc.
     *
     * @param bool $isBuyBtc
     * @param $value
     * @param $price
     * @return float
     */
    private static function convertValue(bool $isBuyBtc, $value, $price) : float
    {
        return $isBuyBtc ? $value / $price : $value * $price;
    }

    /**
     * Round value by currency.
     *
     * @param $value
     * @param $currency
     * @return float
     */
    private static function roundValue($value, $currency) : float
    {
        return round($value, self::isBtc($currency) ? 10 : 2);
    }

    /**
     * Check is BTC.
     *
     * @param string $currency
     * @return bool
     */
    private static function isBtc(string $currency = self::BTC_NAME) : bool
    {
        return mb_strtoupper($currency) === self::BTC_NAME;
    }

    /**
     * Get current price (sell or buy btc).
     *
     * @param bool $isBuyBtc
     * @param $model
     * @return float
     */
    private static function getCurrentPrice(bool $isBuyBtc, $model) : float
    {
        $rateService = new Rates();
        $currency = $rateService->getRates()->filter(function ($item, $key) use ($model, $isBuyBtc) {
            return $key === ($isBuyBtc ? $model->currency_from : $model->currency_to);
        })->first();

        return $isBuyBtc ? $currency['buy'] : $currency['sell'];
    }
}
