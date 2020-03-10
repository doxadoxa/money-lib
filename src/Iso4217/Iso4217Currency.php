<?php
declare(strict_types=1);

namespace Money\Iso4217;

use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\Formatters\Formatter;
use Money\Formatters\Iso4217Formatter;
use Money\FormattingCurrency;

/**
 * Class Iso4217Currency
 * @package Money\Iso4217
 */
class Iso4217Currency implements FormattingCurrency
{
    /** @var string */
    private $alpha3;
    /** @var string */
    private $code;
    /** @var int */
    private $codeNum;
    /** @var array|string[] */
    private $countries;
    /** @var int */
    private $minor;
    /** @var string */
    private $name;
    /** @var array|string[] */
    private $symbols;
    /** @var Formatter|Iso4217Formatter|null */
    private $formatter;

    /**
     * Iso4217Currency constructor.
     * @param string $alpha3
     * @param string $code
     * @param int $codeNum
     * @param array $countries
     * @param int $minor
     * @param string $name
     * @param array $symbols
     * @param Formatter|null $formatter
     */
    public function __construct( string $alpha3, string $code, int $codeNum,
                                 array $countries, int $minor, string $name,
                                 array $symbols,
                                 ?Formatter $formatter = null )
    {
        $this->alpha3 = $alpha3;
        $this->code = $code;
        $this->codeNum = $codeNum;
        $this->countries = $countries;
        $this->minor = $minor;
        $this->name = $name;
        $this->symbols = $symbols;
        $this->formatter = $formatter ?? new Iso4217Formatter();
    }

    /**
     * @return string
     */
    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getCodeNum(): int
    {
        return $this->codeNum;
    }

    /**
     * @return array
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->minor;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getSymbols(): array
    {
        return $this->symbols;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbols[0];
    }

    /**
     * @param float $amount
     * @return string
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function format(float $amount): string
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

    /**
     * @param Formatter $formatter
     */
    public function setFormatter( Formatter $formatter ): void
    {
        $this->formatter = $formatter;
    }
}
