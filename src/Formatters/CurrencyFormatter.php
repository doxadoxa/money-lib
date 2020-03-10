<?php
declare(strict_types=1);

namespace Money\Formatters;

use Money\Currency;
use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\FormattingCurrency;

class CurrencyFormatter extends Formatter
{
    private $disableIso4217Formatting = false;

    /**
     * @param bool $settingValue
     */
    public function setDisableIso4217Formatting( bool $settingValue = false ): void
    {
        $this->disableIso4217Formatting = $settingValue;
    }

    /**
     * @param FormattingCurrency|Currency $currency
     * @param float $amount
     * @return string
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function format( FormattingCurrency $currency, float $amount): string
    {
        if ( !( $currency instanceof Currency ) ) {
            throw new ObjectOfThisClassCantBeFormattedException();
        }

        if ( !$this->disableIso4217Formatting && $currency->getIco4217() ) {
            return $currency->getIco4217()->format( $amount );
        }

        $decimals = $this->decimals ?? $currency->getDecimals();

        $amount = number_format( $amount, $decimals,
            $this->decPoint, $this->thousandsSep );
        $symbol = $currency->getSymbol();

        return str_replace(':symbol', $symbol,
            str_replace(':amount', $amount, $format ?? ':amount :symbol' ) );
    }
}
