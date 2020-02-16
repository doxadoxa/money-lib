<?php
declare(strict_types=1);

use Money\Exceptions\DecimalsCantBeNegativeException;
use Money\Math\ArbitraryFloat;
use PHPUnit\Framework\TestCase;

class ArbitraryFloatTest extends TestCase
{
    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testCreatingWorks()
    {
        $bigInteger = ArbitraryFloat::makeFromFloat(10, 5);
        $this->assertInstanceOf( ArbitraryFloat::class, $bigInteger );
    }

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testCreatingDontWorksWithBadParams()
    {
        $this->expectException( DecimalsCantBeNegativeException::class );
        $number = ArbitraryFloat::makeFromFloat( 10, -5 );
    }

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testCreatingWorksWithEdgeParams()
    {
        $number = ArbitraryFloat::makeFromFloat( 10, 0 );
        $this->assertInstanceOf( ArbitraryFloat::class, $number );
    }

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testMakeFromFloatIsWorks()
    {
        $number = ArbitraryFloat::makeFromFloat( 10, 2 );
        $this->assertEquals('1000', $number->toString());
    }

    /**
     * @throws DecimalsCantBeNegativeException
     */
    public function testToFloatConversionWorksCorrect()
    {
        $number = new ArbitraryFloat('1025', 2);
        $this->assertEquals(10.25, $number->toFloat());
    }
}
