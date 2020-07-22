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
$usd = new Currency('USD', 2);
$amount = Money::make( $ethereum, 0.05 );

// Add operation
$newAmount = $amount->add( Money::make( $ethereum, 0.005 ) );
echo $newAmount->getAmount(); // (float) 0.055

// Sub operation
$newAmount = $amount->sub( Money::make( $ethereum, 0.005 ) );
echo $newAmount->getAmount(); // (float) 0.045

// Comparisons
$amount = Money::make( $usd, 10 );
$newAmount = Money::make( $usd, 10);
$nullAmount = Money::make( $ethereum, 0);
$newNullAmount = Money::make( $usd, 0);

$amount->equals( $newAmount ); // (bool) true
$nullAmount->equals($newNullAmount); // (bool) true

$amount->strictEquals( $newAmount ); // (bool) true
$nullAmount->strictEquals($newNullAmount); // (bool) false

$newAmount = Money::make( $usd, 20 );
$amount->less( $newAmount ); // (bool) true
$amount->more( $newAmount ); // (bool) false

```

### ISO4217
Library supports auto-detection for ISO4217 currencies to add some features, like symbol formatting
(like changing USD symbol to $), default decimals count for currency and getting additional params
like country or full name. You can access ISO4217 object via `getIso4217()` method on currency object.

### Formatting output
You can simply format you money with cast to string via `format` method.
```php
use Money\Currency;
use Money\Money;

$usd = new Currency('USD');
$money = Money::make( $usd, 1000 );
echo $money->format();// $ 1,000.00
```

Also, you can make you own formatter by inherit with `Formatter` class and make you own format. 
Or you can simple change format in formatters constructor:
```php
use Money\Formatters\CurrencyFormatter;
use Money\Currency;
use Money\Money;

$usd = new Currency('USD');
$formatter = new CurrencyFormatter(":amount:symbol", '.', '', 0);
$usd->setFormatter( $formatter );
$money = Money::make( $usd, 1000);
echo $money->format(); // 1000$
```