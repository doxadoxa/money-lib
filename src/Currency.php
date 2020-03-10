<?php
declare(strict_types=1);

namespace Money;

use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;
use Money\Formatters\CurrencyFormatter;
use Money\Formatters\Formatter;
use Money\Formatters\Iso4217Formatter;
use Money\Iso4217\Iso4217;
use Money\Iso4217\Iso4217Currency;
use Money\Iso4217\Iso4217Factory;

/**
 * Class Currency
 */
class Currency implements FormattingCurrency
{
    /** @var string  */
    private $symbol;
    /** @var int */
    private $decimals;
    /** @var Iso4217Currency|null */
    private $iso4217;
    private $formatter;

    /**
     * Currency constructor.
     * @param string $symbol
     * @param int|null $decimals
     * @param Iso4217Factory|null $iso4217
     * @param Formatter|null $formatter
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function __construct( string $symbol, ?int $decimals = null,
                                 ?Iso4217Factory $iso4217 = null, ?Formatter $formatter = null )
    {
        $this->assertSymbolIsValid( $symbol );
        $this->symbol = $symbol;

        $this->iso4217 = ( $iso4217 ?? new Iso4217() )
            ->makeIso4217Currency( $symbol, $formatter ? new Iso4217Formatter( $formatter->getFormat() ) : null );
        $this->formatter = $formatter ?? new CurrencyFormatter();

        if ( $decimals === null && $this->iso4217 !== null ) {
            $decimals = $this->iso4217->getDecimals();
        }

        $this->assertDecimalsIsValid( $decimals );
        $this->decimals = $decimals;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getLabel(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * @return Iso4217Currency|null
     */
    public function getIco4217(): ?Iso4217Currency
    {
        return $this->iso4217;
    }

    /**
     * @param Currency $currency
     * @return bool
     */
    public function equals( Currency $currency ): bool
    {
        return $this->getSymbol() == $currency->getSymbol() &&
            $this->getDecimals() == $currency->getDecimals();
    }

    /**
     * @param string $label
     * @throws CurrencyLabelIsWrongException
     */
    private function assertSymbolIsValid(string $label ): void
    {
        if ( !preg_match("/^[A-Z]{3,4}$/", $label ) ) {
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

    /**
     * @param float $amount
     * @return string
     * @throws Exceptions\ObjectOfThisClassCantBeFormattedException
     */
    public function format( float $amount ): string
    {
        return $this->formatter->format( $this, $amount );
    }

    /**
     * @return Formatter
     */
    public function getFormatter(): Formatter
    {
        return $this->formatter;
    }

    public function setFormatter( Formatter $formatter ): void
    {
        $this->getIco4217()->setFormatter( new Iso4217Formatter(
            $formatter->getFormat(), $formatter->getDecPoint(),
            $formatter->getThousandsSep(), $formatter->getDecimals() ) );
        $this->formatter = $formatter;
    }
}
