<?php
declare(strict_types=1);
include "./vendor/autoload.php";

use Money\Currency;
use Money\Money;

$bitcoin = new Currency('BTC', 8);
$ethereum = new Currency('ETH', 18);
$usd = new Currency('USD', 2);
$fantics = new Currency('FNTK', 0);

echo "Try make Ethereum." . PHP_EOL;


echo "Make really small value." . PHP_EOL;
$amount = Money::make( $ethereum, 0.000000000000000005 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In wei: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

$amount = Money::make( $ethereum, 3 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In wei: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;



$amount = Money::make( $bitcoin, 0.5828 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In satoshis: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

$amount = Money::make( $bitcoin, 10.015 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In satoshis: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

echo "Try to add 3 bitcoins here." . PHP_EOL;
$amount = $amount->add( Money::make( $bitcoin, 3 ) );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In satoshis: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

echo "Try to sub 10 bitcoins here." . PHP_EOL;
$amount = $amount->sub( Money::make( $bitcoin, 10 ) );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In satoshis: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;


echo "Try make USD." . PHP_EOL;

$amount = Money::make( $usd, 30 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In cents: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

$amount = Money::make( $fantics, 30 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In base: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

$amount = Money::make( new Currency('STR', 1), 30.5 );
echo "Amount: " . $amount->getAmount() . PHP_EOL;
echo "Currency: " . $amount->getCurrency()->getSymbol() . PHP_EOL;
echo "In base: " . $amount->getStringAmount() . PHP_EOL;
echo "===================" . PHP_EOL;

