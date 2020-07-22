<?php
declare(strict_types=1);

namespace Tests;

use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
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
        $amount = new Money( $this->usd, BigDecimal::of( 10 ) );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     */
    public function testIsCreatesWithFloatFactoryMethod()
    {
        $amount = Money::make( $this->usd, 30 );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     */
    public function testIsCreatesWithFloatNull()
    {
        $amount = Money::make( $this->usd, 0 );
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
     *
     */
    public function testIsCreatesWithIntFactoryMethod()
    {
        $amount = Money::makeFromInt( $this->usd, 10 );
        $this->assertInstanceOf( Money::class, $amount );
    }

    /**
     *
     */
    public function testIsCreatesWithBigIntegerFactoryMethod()
    {
        $amount = Money::makeFromBigInteger( $this->usd, BigInteger::of( 10 ) );
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
     */
    public function testIsReturnCorrectAmountsWithBaseConstructor()
    {
        $amount = new Money( $this->usd, BigDecimal::of( 0.1 ) );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertTrue( BigDecimal::of( 0.1 )->isEqualTo( $amount->getBaseAmount() ) );
        $this->assertEquals( '10', $amount->getStringAmount() );
    }

    /**
     */
    public function testIsReturnCorrectAmountsWithFloatFactoryMethod()
    {
        $amount = Money::make( $this->usd, 0.1 );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertTrue( BigDecimal::of( 0.1 )->isEqualTo( $amount->getBaseAmount() ) );
        $this->assertEquals( '10', $amount->getStringAmount() );
    }

    /**
     * @throws StringIsNotValidIntegerException
     */
    public function testIsReturnCorrectAmountsWithStringFactoryMethod()
    {
        $amount = Money::makeFromString( $this->usd, '10' );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertTrue( BigDecimal::of( 0.1 )->isEqualTo( $amount->getBaseAmount() ) );
        $this->assertEquals( '10', $amount->getStringAmount() );
        $this->assertEquals( 10, $amount->getIntAmount() );
    }

    public function testIsReturnCorrectAmountsWithIntFactoryMethod()
    {
        $amount = Money::makeFromInt( $this->usd, 10 );
        $this->assertEquals( 0.1, $amount->getAmount() );
        $this->assertTrue( BigDecimal::of(0.1)->isEqualTo( $amount->getBaseAmount() ) );
        $this->assertEquals( '10', $amount->getStringAmount() );
        $this->assertEquals( 10, $amount->getIntAmount() );
    }

    /**
     */
    public function testMoneyCanBeNegative()
    {
        $first = Money::make( $this->usd, -30 );
        $this->assertEquals( -30, $first->getAmount() );
    }

    /**
     */
    public function testMoneyCanBePositiveAndReal()
    {
        $fist = Money::make( $this->usd, 0.85 );
        $this->assertEquals( 0.85, $fist->getAmount() );
    }

    /**
     */
    public function testMoneyCanBeNegativeAndReal()
    {
        $fist = Money::make( $this->usd, -0.85 );
        $this->assertEquals( -0.85, $fist->getAmount() );
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
     * @throws DecimalsCantBeNegativeException
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
     * @throws DecimalsCantBeNegativeException
     */
    public function testSubNotWorksOnDifferentMoneyCurrencies()
    {
        $this->expectException( DifferentCurrenciesCantBeOperatedException::class );
        $first = Money::make( $this->usd, 10 );
        $second = Money::make( $this->bitcoin, 10 );
        $first->sub( $second );
    }

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testGetCurrencyIsWorks()
    {
        $usd = clone $this->usd;
        $first = Money::make( $this->usd, 10 );

        $this->assertTrue( $first->getCurrency()->equals( $usd ) );
    }

    /**
     * @throws DecimalsCantBeNegativeException
     * @throws DifferentCurrenciesCantBeOperatedException
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

    /**
     * @throws DecimalsCantBeNegativeException
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
     *
     */
    public function testEqualsWithNullAmountReturnTrueOnDifferentCurrencies()
    {
        $first = Money::make($this->usd, 0);
        $second = Money::make($this->rub, 0);

        $this->assertTrue($first->equals($second));
        $this->assertFalse($first->equals($second, true));
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
     * @throws DecimalsCantBeNegativeException
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
     * @throws DecimalsCantBeNegativeException
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
     * @throws DecimalsCantBeNegativeException
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

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testFormatIsWorks()
    {
        $money = Money::make( $this->usd, 1000 );

        $this->assertEquals( '$ 1,000.00', $money->format() );
    }
}
