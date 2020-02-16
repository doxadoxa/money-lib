<?php
declare(strict_types=1);

namespace Tests;

use Money\Currency;
use Money\Exceptions\CurrencyLabelIsWrongException;
use Money\Exceptions\DecimalsCantBeNegativeException;
use Money\Exceptions\DifferentCurrenciesCantBeOperatedException;
use Money\Exceptions\StringIsNotValidIntegerException;
use Money\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{

    private $usd;
    private $rub;
    private $bitcoin;
    private $ethereum;

    /**
     * @throws CurrencyLabelIsWrongException
     * @throws DecimalsCantBeNegativeException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->usd = new Currency('USD', 2);
        $this->rub = new Currency('RUB', 2);
        $this->bitcoin = new Currency('BTC', 8);
        $this->ethereum = new Currency('ETH', 18);
    }

    /**
     *
     */
    public function testIsCreatesWithConstructor()
    {
        $amount = new Money( $this->usd, gmp_init( 10 ) );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     *
     */
    public function testIsCreatesWithFloatFactoryMethod()
    {
        $amount = Money::make( $this->usd, 30 );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     * @throws StringIsNotValidIntegerException
     */
    public function testIsCreatesWithStringFactoryMethod()
    {
        $amount = Money::makeFromString( $this->usd, '30' );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     * @throws StringIsNotValidIntegerException
     */
    public function testIsNotCreatesWithStringFactoryMethodWithWrongParams()
    {
        $this->expectException( StringIsNotValidIntegerException::class );
        Money::makeFromString( $this->usd, '0.8158528' );
    }

    /**
     *
     */
    public function testIsReturnCorrectAmountsWithBaseConstructor()
    {
        $amount = new Money( $this->usd, gmp_init( 10 ) );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertEquals( gmp_init( 10 ), $amount->getBaseAmount() );
        $this->assertEquals( '10', $amount->getStringAmount() );
    }

    /**
     *
     */
    public function testIsReturnCorrectAmountsWithFloatFactoryMethod()
    {
        $amount = Money::make( $this->usd, 0.1 );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertEquals( gmp_init( 10 ), $amount->getBaseAmount() );
        $this->assertEquals( '10', $amount->getStringAmount() );
    }

    /**
     *
     */
    public function testIsReturnCorrectAmountsWithStringFactoryMethod()
    {
        $amount = Money::makeFromString( $this->usd, '10' );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertEquals( gmp_init( 10 ), $amount->getBaseAmount() );
        $this->assertEquals( '10', $amount->getStringAmount() );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testAddWorksCorrect()
    {
        $firstValue = 10;
        $secondValue = 10;
        $resultValue = $firstValue + $secondValue;

        $first = Money::make( $this->usd, $firstValue );
        $second = Money::make( $this->usd, $secondValue );

        $this->assertEquals( $resultValue, $first->add( $second )->getAmount() );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testSubWorksCorrect()
    {
        $firstValue = 10;
        $secondValue = 5;
        $resultValue = $firstValue - $secondValue;

        $first = Money::make( $this->usd, $firstValue );
        $second = Money::make( $this->usd, $secondValue );

        $this->assertEquals( $resultValue, $first->sub( $second )->getAmount() );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testAddNotWorksOnDifferentMoneyCurrencies()
    {
        $this->expectException( DifferentCurrenciesCantBeOperatedException::class );
        $first = Money::make( $this->usd, 10 );
        $second = Money::make( $this->bitcoin, 10 );
        $first->add( $second );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testSubNotWorksOnDifferentMoneyCurrencies()
    {
        $this->expectException( DifferentCurrenciesCantBeOperatedException::class );
        $first = Money::make( $this->usd, 10 );
        $second = Money::make( $this->bitcoin, 10 );
        $first->sub( $second );
    }

    /**
     *
     */
    public function testGetCurrencyIsWorks()
    {
        $usd = clone $this->usd;
        $first = Money::make( $this->usd, 10 );

        $this->assertTrue( $first->getCurrency()->equals( $usd ) );
    }

    /**
     *
     */
    public function testWorksWithSmallValues()
    {
        $amount = 0.000000000000000005;
        $first = Money::make( $this->ethereum, $amount );
        $second = Money::make( $this->ethereum, $amount );

        $result = $amount + $amount;

        $this->assertEquals( '5', $first->getStringAmount() );
        $this->assertEquals( '10', $first->add( $second )->getStringAmount() );
        $this->assertEquals( $result, $first->add( $second )->getAmount() );
    }

    /***
     *
     */
    public function testEqualsWorks()
    {
        $amount = 30;
        $first = Money::make( $this->usd, $amount );
        $second = Money::make( $this->usd, $amount );

        $this->assertTrue( $first->equals( $second ) );
    }

    /**
     *
     */
    public function testEqualsWithDifferentCurrenciesReturnsFalse()
    {
        $amount = 30;
        $first = Money::make( $this->usd, $amount );
        $second = Money::make( $this->rub, $amount );

        $this->assertFalse( $first->equals( $second ) );
    }

    /**
     *
     */
    public function testEqualsWithDifferentAmountReturnsFalse()
    {
        $first = Money::make( $this->usd, 30 );
        $second = Money::make( $this->usd, 20 );

        $this->assertFalse( $first->equals( $second ) );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testLessWorks()
    {
        $firstAmount = 20;
        $secondAmount = 30;
        $expected = $firstAmount < $secondAmount;
        $wrongExpected = $secondAmount < $firstAmount;

        $first = Money::make( $this->usd, $firstAmount );
        $second = Money::make( $this->usd, $secondAmount );

        $this->assertEquals( $expected, $first->less( $second ) );
        $this->assertEquals( $wrongExpected, $second->less( $first ) );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testLessThrowErrorWithDifferenceCurrencies()
    {
        $this->expectException( DifferentCurrenciesCantBeOperatedException::class );

        $firstAmount = 20;
        $first = Money::make( $this->usd, $firstAmount );
        $second = Money::make( $this->rub, $firstAmount );

        $first->less( $second );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testMoreWorks()
    {
        $firstAmount = 30;
        $secondAmount = 20;
        $expected = $firstAmount > $secondAmount;
        $wrongExpected = $secondAmount > $firstAmount;

        $first = Money::make( $this->usd, $firstAmount );
        $second = Money::make( $this->usd, $secondAmount );

        $this->assertEquals( $expected, $first->more( $second ) );
        $this->assertEquals( $wrongExpected, $second->more( $first ) );
    }

    /**
     * @throws DifferentCurrenciesCantBeOperatedException
     */
    public function testMoreThrowErrorWithDifferenceCurrencies()
    {
        $this->expectException( DifferentCurrenciesCantBeOperatedException::class );

        $firstAmount = 20;
        $first = Money::make( $this->usd, $firstAmount );
        $second = Money::make( $this->rub, $firstAmount );

        $first->more( $second );
    }
}
