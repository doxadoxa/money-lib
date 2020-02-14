<?php
declare(strict_types=1);

namespace Tests;

use Money\Currency;
use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;
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
        $this->assertEquals( 'USD', $currency->getLabel() );
    }
}
