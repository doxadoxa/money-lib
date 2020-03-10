<?php
declare(strict_types=1);

namespace Tests\Formatters;

use Money\Exceptions\ObjectOfThisClassCantBeFormattedException;
use Money\Formatters\Iso4217Formatter;
use Money\Iso4217\Iso4217;
use PHPUnit\Framework\TestCase;

class Iso4217FormatterTest extends TestCase
{
    private $currency;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->currency = (new Iso4217())->makeIso4217Currency( 'USD' );
    }

    /**
     * @throws ObjectOfThisClassCantBeFormattedException
     */
    public function testIso4217FormatterWorks()
    {
        $formatter = new Iso4217Formatter(':symbol:amount');
        $formatted = $formatter->format( $this->currency, 0 );
        $this->assertEquals('$0.00', $formatted );
    }

}
