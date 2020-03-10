<?php
declare(strict_types=1);

namespace Money\Iso4217;

use Money\Formatters\Formatter;

/**
 * Interface Iso4217Factory
 * @package Money\Iso4217
 */
interface Iso4217Factory
{
    public function makeIso4217Currency( string $code, ?Formatter $formatter = null ): ?Iso4217Currency;
}
