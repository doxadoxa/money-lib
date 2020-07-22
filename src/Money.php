<?php
declare(strict_types=1);

namespace Money;

use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\Exceptions\StringIsNotValidIntegerException;
use Money\Exceptions\DifferentCurrenciesCantBeOperatedException;

/**
 * Class Money
 * @package Money
 */
class Money
{
    /** @var BigDecimal|BigNumber|null */
    private $amount;
    /** @var Currency */
    private $currency;

    /**
     * Money constructor.
     * @param Currency $currency
     * @param BigDecimal|null $amount
     */
    public function __construct( Currency $currency, ?BigDecimal $amount = null )
    {
        if ( $amount === null ) {
            $amount = BigDecimal::of(0)
                ->toScale( $currency->getDecimals() );
        } else {
            $amount = $amount->toScale( $currency->getDecimals() );
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
        $baseAmount = BigDecimal::of( $amount )
            ->toScale( $currency->getDecimals() );

        if ( $amount == 0 ) {
            $baseAmount = BigDecimal::of( 0 )
                ->toScale( $currency->getDecimals() );
        }

        return new self( $currency, $baseAmount );
    }

    /**
     * @param Currency $currency
     * @param int $amount
     * @return static
     */
    public static function makeFromInt( Currency $currency, int $amount = 0 ): self
    {
        return new self( $currency, BigDecimal::ofUnscaledValue( $amount, $currency->getDecimals() ) );
    }

    /**
     * @param Currency $currency
     * @param BigInteger $amount
     * @return static
     */
    public static function makeFromBigInteger( Currency $currency, ?BigInteger $amount = null ): self
    {
        return new self( $currency, BigDecimal::ofUnscaledValue( $amount ?? BigInteger::of(0), $currency->getDecimals() ) );
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
        return new self( $currency, BigDecimal::ofUnscaledValue( $amount, $currency->getDecimals() ) );
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->getBaseAmount()
            ->toFloat();
    }

    /**
     * @return BigDecimal
     */
    public function getBaseAmount(): BigDecimal
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStringAmount(): string
    {
        return (string) $this->getBaseAmount()
            ->getUnscaledValue();
    }

    /**
     * @return int
     */
    public function getIntAmount(): int
    {
        return $this->getBaseAmount()->getUnscaledValue()->toInt();
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
        $amount = $this->getBaseAmount()->plus( $money->getBaseAmount() );
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
        $amount = $this->getBaseAmount()->minus( $money->getBaseAmount() );
        return new self( $this->currency, $amount );
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function equals(Money $money): bool
    {
        if (
            $this->getBaseAmount()->isEqualTo($money->getBaseAmount()) &&
            $this->getBaseAmount()->isEqualTo(BigDecimal::of(0))
        ) {
            return true;
        }

        return $this->strictEquals($money);
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function strictEquals(Money $money): bool
    {
        return $this->getCurrency()->equals($money->getCurrency() ) &&
            $this->getBaseAmount()->isEqualTo($money->getBaseAmount());
    }

    /**
     * @param Money $money
     * @return bool
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function less( Money $money ): bool
    {
        $this->assertOperationsIsAvailable( $money );
        return $this->getBaseAmount()->isLessThan( $money->getBaseAmount() );
    }

    /**
     * @param Money $money
     * @return bool
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function more( Money $money ): bool
    {
        $this->assertOperationsIsAvailable( $money );
        return $this->getBaseAmount()->isGreaterThan( $money->getBaseAmount() );
    }

    /**
     * @return string
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function format(): string
    {
        return $this->currency->format(
            $this->getAmount()
        );
    }

    /**
     * @return string
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function __toString(): string
    {
        return $this->format();
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
        if ( !preg_match("/^[0-9\-]+$/", $value) ) {
            throw new StringIsNotValidIntegerException();
        }
    }
}

