<?php
declare(strict_types=1);

namespace Money;

use Money\Formatters\Formatter;

interface FormattingCurrency
{
    public function getDecimals(): int;
    public function getSymbol(): string;
    public function getFormatter(): Formatter;
    public function setFormatter( Formatter $formatter ): void;
    public function format( float $amount ): string;
}
