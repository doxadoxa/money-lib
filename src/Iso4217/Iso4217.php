<?php
declare(strict_types=1);

namespace Money\Iso4217;

use Money\Formatters\Formatter;
use Money\Formatters\Iso4217Formatter;

/**
 * Class Iso4217
 * @package Money\Iso4217
 */
class Iso4217 implements Iso4217Factory
{
    /**
     * @param string $code
     * @param Formatter $formatter
     * @return Iso4217Currency|null
     */
    public function makeIso4217Currency( string $code, ?Formatter $formatter = null ): ?Iso4217Currency
    {
        $currency = $this->data()[ strtoupper( $code ) ] ?? null;

        if ( $currency === null ) {
            return null;
        }

        return new Iso4217Currency(
            $currency['alpha3'],
            $code,
            $currency['code_num'],
            $currency['countries'],
            $currency['minor'],
            $currency['name'],
            $currency['symbols'],
            $formatter ?? new Iso4217Formatter( $currency['format'] ?? null )
        );
    }

    /**
     * @return array
     */
    private function data(): array
    {
        return include("currencies.php");
    }
}
