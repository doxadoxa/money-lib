<?php
declare(strict_types=1);

namespace Money\Formatters;

use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\FormattingCurrency;
use Money\Iso4217\Iso4217Currency;

/**
 * Class Iso4217Formatter
 * @package Money\Formatters
 */
class Iso4217Formatter extends Formatter
{
    /**
     * @param FormattingCurrency $currency
     * @param float $amount
     * @return string
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function format( FormattingCurrency $currency, float $amount ): string
    {
        if ( !( $currency instanceof Iso4217Currency ) ) {
            throw new ObjectOfThisClassCantBeFormattedException();
        }

        $decimals = $this->decimals ?? $currency->getDecimals();

        $amount = number_format( $amount, $decimals,
            $this->decPoint, $this->thousandsSep );
        $symbol = $currency->getSymbol();

        return str_replace(':symbol', $symbol,
            str_replace(':amount', $amount, $this->format ) );
    }
}
