<?php
declare(strict_types=1);

namespace Money;

use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;

/**
 * Class Currency
 */
class Currency
{
    private $label;
    private $decimals;

    /**
     * Currency constructor.
     * @param string $label
     * @param int $decimals
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function __construct( string $label, int $decimals )
    {
        $this->assertLabelIsValid( $label );
        $this->assertDecimalsIsValid( $decimals );

        $this->label = $label;
        $this->decimals = $decimals;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @param Currency $currency
     * @return bool
     */
    public function equals( Currency $currency ): bool
    {
        return $this->getLabel() == $currency->getLabel() &&
            $this->getDecimals() == $currency->getDecimals();
    }

    /**
     * @param string $label
     * @throws CurrencyLabelIsWrongException
     */
    private function assertLabelIsValid( string $label ): void
    {
        if ( !preg_match("/[A-Z]{3,4}/", $label ) ) {
            throw new CurrencyLabelIsWrongException();
        }
    }

    /**
     * @param int $decimals
     * @throws DecimalsCantBeNegativeException
     */
    private function assertDecimalsIsValid( int $decimals ): void
    {
        if ( $decimals < 0 ) {
            throw new DecimalsCantBeNegativeException();
        }
    }
}
