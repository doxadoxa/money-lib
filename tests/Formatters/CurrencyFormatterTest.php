<?php
declare(strict_types=1);

namespace Tests\Formatters;

use Money\Currency;
use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;
use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\Formatters\CurrencyFormatter;
use PHPUnit\Framework\TestCase;

class CurrencyFormatterTest extends TestCase
{
    private $currency;
    private $isoFallbackCurrency;

    /**
     * @throws DecimalsCantBeNegativeException
     * @throws CurrencyLabelIsWrongException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->currency = new Currency('KKK', 2);
        $this->isoFallbackCurrency = new Currency('USD');
    }

    /**
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function testFormatterWorks()
    {
        $formatter = new CurrencyFormatter();
        $formatted = $formatter->format( $this->currency, 0 );
        $this->assertEquals('0.00 KKK', $formatted );
    }

    /**
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function testIsoFallbackWorks()
    {
        $formatter = new CurrencyFormatter();
        $formatted = $formatter->format( $this->isoFallbackCurrency, 0 );
        $this->assertEquals('$ 0.00', $formatted );
    }

    /**
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function testIsoFallbackDisableWorks()
    {
        $formatter = new CurrencyFormatter();
        $formatter->setDisableIso4217Formatting( true );
        $formatted = $formatter->format( $this->isoFallbackCurrency, 0 );
        $this->assertEquals('0.00 USD', $formatted );
    }
}
