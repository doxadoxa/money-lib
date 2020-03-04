<?php
declare(strict_types=1);

namespace Money\Math;

use Money\Exceptions\DecimalsCantBeNegativeException;

class ArbitraryFloat
{
    private $decimals;
    private $amount;

    /**
     * ArbitraryFloat constructor.
     * @param string $amount
     * @param int $decimals
     * @throws DecimalsCantBeNegativeException
     */
    public function __construct( string $amount, int $decimals )
    {
        $this->assertDecimalsIsNotNegative( $decimals );

        $this->amount = $amount;
        $this->decimals = $decimals;
    }

    /**
     * @param float $amount
     * @param int $decimals
     * @return ArbitraryFloat
     * @throws DecimalsCantBeNegativeException
     */
    public static function makeFromFloat( float $amount, int $decimals ): ArbitraryFloat
    {
        $stringValue = number_format( $amount, $decimals, '', '' );

        $exploded = explode('.', $stringValue );
        $base = $exploded[0];
        $mantissa = $exploded[1] ?? '';

        return new self( ltrim( $base . $mantissa, '0' ), $decimals );
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function toFloat(): float
    {
        $signs = implode('', array_fill(0, $this->decimals, '0') );
        $length = strlen( $this->amount ) > $this->decimals ? strlen( $this->amount ) : $this->decimals;
        $amount = str_pad( $this->amount, $length, '0', STR_PAD_LEFT );
        $signs = str_pad( $signs, $length, '0' );

        $amount = $amount | $signs;

        if ( $this->decimals > 0 ) {
            $base = substr($amount, 0, -$this->decimals);
            $mantissa = substr($amount, -$this->decimals);
        } else {
            $base = $amount;
            $mantissa = '';
        }

        $stringValue = $base . '.' . $mantissa;

        return (float) $stringValue;
    }

    /**
     * @param int $decimals
     * @throws DecimalsCantBeNegativeException
     */
    private function assertDecimalsIsNotNegative( int $decimals ): void
    {
        if ( $decimals < 0 ) {
            throw new DecimalsCantBeNegativeException();
        }
    }
}
