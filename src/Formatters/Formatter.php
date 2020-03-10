<?php
declare(strict_types=1);

namespace Money\Formatters;

use Money\FormattingCurrency;

/**
 * Class Formatter
 */
abstract class Formatter
{
    /** @var string|null */
    protected $format = null;
    /** @var string */
    protected $decPoint;
    /** @var string */
    protected $thousandsSep;
    /** @var int|null */
    protected $decimals;

    /**
     * Formatter constructor.
     * @param string|null $format
     * @param string $decPoint
     * @param string $thousandsSep
     * @param int|null $decimals
     */
    public function __construct( ?string $format = null, string $decPoint = '.',
                                 string $thousandsSep = ',' , ?int $decimals = null  )
    {
        if ( $format ) {
            $this->format = $format;
        }

        $this->decPoint = $decPoint;
        $this->thousandsSep = $thousandsSep;
        $this->decimals = $decimals;
    }

    /**
     * @return string|null
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getDecPoint(): string
    {
        return $this->decPoint;
    }

    /**
     * @return string
     */
    public function getThousandsSep(): string
    {
        return $this->thousandsSep;
    }

    /**
     * @return int|null
     */
    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    /**
     * @param FormattingCurrency $currency
     * @param float $amount
     * @return string
     */
    abstract public function format( FormattingCurrency $currency, float $amount ): string;
}
