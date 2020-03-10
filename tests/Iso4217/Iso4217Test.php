<?php
declare(strict_types=1);

namespace Tests\Iso4217;

use Money\Formatters\Formatter;
use Money\FormattingCurrency;
use Money\Iso4217\Iso4217;
use Money\Iso4217\Iso4217Currency;
use PHPUnit\Framework\TestCase;

class Iso4217Test extends TestCase
{
    /**
     *
     */
    public function testIso4217FactoryWorks(): void
    {
        $iso4217 = new Iso4217();
        $currency = $iso4217->makeIso4217Currency('USD' );

        $this->assertInstanceOf( Iso4217Currency::class, $currency );
    }

    /**
     *
     */
    public function testIso4217FactoryReturnNullOnNonExistedSymbol(): void
    {
        $iso4217 = new Iso4217();
        $currency = $iso4217->makeIso4217Currency('KKK' );

        $this->assertNull( $currency );
    }

    /**
     *
     */
    public function testFormatterInitWorks()
    {
        $iso4217 = new Iso4217();
        $currency = $iso4217->makeIso4217Currency('USD' );

        $this->assertInstanceOf( Formatter::class, $currency->getFormatter() );
    }

    /**
     *
     */
    public function testFormatterIsReplaceable()
    {
        $iso4217 = new Iso4217();
        $formatter = new class extends Formatter {
            public function format(FormattingCurrency $currency, float $amount): string
            {
                return 'test formatter has been replaced';
            }
        };
        $currency = $iso4217->makeIso4217Currency('USD', $formatter );

        $this->assertEquals( 'test formatter has been replaced', $currency->format(0) );
    }
}
