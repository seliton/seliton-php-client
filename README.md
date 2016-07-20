# Seliton PHP Client

## Requirements

PHP 5.3 and later.

## Installation

Download [latest version](https://github.com/seliton/seliton-php-client/archive/master.zip).

Include the `init.php` file:

```php
require_once('/path/to/seliton-php-client/init.php');
```

## Getting Started

Simple usage looks like:

```php
require_once '/path/to/seliton-php-client/init.php';

use Seliton\Client\Seliton;

$seliton = new Seliton('http://dev-1.myseliton.com/api/v1/');

$page = $seliton->page()->create();

print_r($page);
```

## Documentation

Please see http://dev.seliton.com/api/docs/ for up-to-date documentation.

## Development

Install dependencies:

``` bash
composer install
```

## Tests

Install dependencies as mentioned above (which will resolve [PHPUnit](http://packagist.org/packages/phpunit/phpunit)), then you can run the test suite:

```bash
./vendor/bin/phpunit
```

Or to run an individual test file:

```bash
./vendor/bin/phpunit tests/PageTestCase.php
```
