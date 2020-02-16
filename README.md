# MoneyLib for PHP

[![Build Status](https://travis-ci.org/doxadoxa/money-lib.svg?branch=master)](https://travis-ci.org/doxadoxa/money-lib)

Simple implementation for Fouler Money Pattern with GMP. 
You can use this lib for cryptocurrencies also.

## How to install
Install via composer:
```bash
composer require doxadoxa/money-lib
```

## How to use
### Basic
Simple do like this:
```php
use Money\Currency;
use Money\Money;

$bitcoin = new Currency('BTC', 8);
$amount = Money::make( $bitcoin, 0.1858);
echo $amount->getAmount(); // (float) 0.1858
echo $amount->getStringAmount(); // (string) "18580000"
```

Also, you can make money from every precision you want:
```php
use Money\Currency;
use Money\Money;

$ethereum = new Currency('ETH', 18);
$amount = Money::make( $ethereum, 0.000000000000000005);
echo $amount->getAmount(); // (float) 5.0E-18
echo $amount->getStringAmount(); // (string) "5"
```

### Available operations
You can use default math operations over the money 
space — addition and substraction. All operations are side-effect secure.
```php
use Money\Currency;
use Money\Money;

$ethereum = new Currency('ETH', 18);
$amount = Money::make( $ethereum, 0.05 );
$newAmount = $amount->add( Money::make( $ethereum, 0.005 ) );
echo $newAmount->getAmount(); // (float) 0.055

$newAmount = $amount->sub( Money::make( $ethereum, 0.005 ) );
echo $newAmount->getAmount(); // (float) 0.045
```