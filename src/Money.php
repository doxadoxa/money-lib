<?php
declare(strict_types=1);

namespace Money;

use GMP;
use Money\Exceptions\StringIsNotValidIntegerException;
use Money\Math\BigInteger;
use Money\Exceptions\DifferentCurrenciesCantBeOperatedException;

/**
 * Class Money
 * @package Money
 */
class Money
{
    private $amount;
    private $currency;

    /**
     * Money constructor.
     * @param Currency $currency
     * @param GMP|null $amount
     */
    public function __construct( Currency $currency, ?GMP $amount = null )
    {
        if ( $amount === null ) {
            $amount = gmp_init(0);
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param Currency $currency
     * @param float $amount
     * @return static
     */
    public static function make( Currency $currency, float $amount = 0 ): self
    {
        $bigIntString = BigInteger::makeFromFloat( $amount, $currency->getDecimals() );
        $baseAmount = gmp_init( $bigIntString->toString() );
        return new self( $currency, $baseAmount );
    }

    /**
     * @param Currency $currency
     * @param int $amount
     * @return static
     */
    public static function makeFromInt( Currency $currency, int $amount = 0 ): self
    {
        return new self( $currency, gmp_init( $amount ) );
    }

    /**
     * @param Currency $currency
     * @param string $amount
     * @return static
     * @throws StringIsNotValidIntegerException
     */
    public static function makeFromString( Currency $currency, string $amount = '0' ): self
    {
        self::assertStringIsIntegerFormatted( $amount );
        return new self( $currency, gmp_init( $amount ) );
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return ( new BigInteger( gmp_strval( $this->amount ), $this->currency->getDecimals()  ) )->toFloat();
    }

    /**
     * @return GMP
     */
    public function getBaseAmount(): GMP
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStringAmount(): string
    {
        return gmp_strval( $this->amount );
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Money $money
     * @return Money
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function add( Money $money ): self
    {
        $this->assertOperationsIsAvailable( $money );
        $amount = gmp_add( $this->getBaseAmount(), $money->getBaseAmount() );

        return new self( $this->currency, $amount );
    }

    /**
     * @param Money $money
     * @return $this
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function sub( Money $money ): self
    {
        $this->assertOperationsIsAvailable( $money );
        $amount = gmp_sub( $this->getBaseAmount(), $money->getBaseAmount() );

        return new self( $this->currency, $amount );
    }

    /**
     * @param Money $money
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    private function assertOperationsIsAvailable( Money $money )
    {
        if ( !$this->currency->equals( $money->currency ) ) {
            throw new DifferentCurrenciesCantBeOperatedException();
        }
    }

    /**
     * @param string $value
     * @throws StringIsNotValidIntegerException
     */
    private static function assertStringIsIntegerFormatted( string $value ): void
    {
        if ( !preg_match("/^[0-9]+$/", $value) ) {
            throw new StringIsNotValidIntegerException();
        }
    }
}

