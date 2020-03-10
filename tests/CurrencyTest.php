<?php
declare(strict_types=1);

namespace Tests;

use Money\Currency;
use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;
use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\Formatters\CurrencyFormatter;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyTest
 * @package Tests
 */
class CurrencyTest extends TestCase
{
    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testCreatesWithValidParams(): void
    {
        $this->expectNotToPerformAssertions();
        new Currency('USD', 2);
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testNotCreatesWithLongCurrencyCode(): void
    {
        $this->expectException( CurrencyLabelIsWrongException::class );
        new Currency('USDTTTT', 2);
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testNotCreatesWithShortCurrencyCode(): void
    {
        $this->expectException( CurrencyLabelIsWrongException::class );
        new Currency('US', 2);
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testNotCreatesWithNegativeDecimals(): void
    {
        $this->expectException( DecimalsCantBeNegativeException::class );
        new Currency('USD', -5);
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testEqualsWorksOnCorrectValues(): void
    {
        $firstCurrency = new Currency('USD', 2);
        $secondCurrency = new Currency('USD', 2);

        $this->assertTrue(
            $firstCurrency->equals( $secondCurrency )
        );
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testEqualsWorksOnNonCorrectValues(): void
    {
        $firstCurrency = new Currency('USD', 2);
        $secondCurrency = new Currency('BTC', 8);

        $this->assertFalse(
            $firstCurrency->equals( $secondCurrency )
        );
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testGetDecimalReturnCorrectValue(): void
    {
        $currency = new Currency('USD', 2 );
        $this->assertEquals( 2, $currency->getDecimals() );
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    public function testGetCurrencyReturnCorrectValue(): void
    {
        $currency = new Currency('USD', 2 );
        $this->assertEquals( 'USD', $currency->getSymbol() );
    }

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function testFormattersIsChangeable()
    {
        $usd = new Currency('USD');

        $usd->setFormatter( new CurrencyFormatter( ":symbol :amount", '.', '' ) );
        $this->assertEquals( '$ 1000.00', $usd->format( 1000 ) );

        $usd->setFormatter( new CurrencyFormatter(":symbol :amount", '.', '', 0) );
        $this->assertEquals( '$ 1000', $usd->format( 1000 ) );

        $usd->setFormatter( new CurrencyFormatter(":amount:symbol", '.', '', 0) );
        $this->assertEquals( '1000$', $usd->format( 1000 ) );

        $formatter = new CurrencyFormatter(":amount :symbol");
        $formatter->setDisableIso4217Formatting( true );
        $usd->setFormatter( $formatter );
        $this->assertEquals( '1,000.00 USD', $usd->format( 1000 ) );
    }
}
