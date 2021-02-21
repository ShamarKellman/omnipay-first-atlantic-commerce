# Omnipay: First Atlantic Commerce

First Atlantic Commerce driver for the Omnipay PHP payment processing library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shamarkellman/omnipay-first-atlantic-commerce.svg?style=flat-square)](https://packagist.org/packages/shamarkellman/omnipay-first-atlantic-commerce)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/shamarkellman/omnipay-first-atlantic-commerce/Tests?label=tests)](https://github.com/shamarkellman/omnipay-first-atlantic-commerce/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/shamarkellman/omnipay-first-atlantic-commerce.svg?style=flat-square)](https://packagist.org/packages/shamarkellman/omnipay-first-atlantic-commerce)

Omnipay is a framework agnostic, multi-gateway payment processing library for PHP. This package implements First
Atlantic Commerce support for Omnipay.

## Support us

We invest a lot of resources into creating opensource products.

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.
You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards
on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require shamarkellman/omnipay-first-atlantic-commerce
```

## Usage

### Authorize Request

```php
$gateway = Omnipay::create('FirstAtlanticCommerce');
$gateway->setMerchantId('123456789');
$gateway->setMerchantPassword('abc123');

$cardData = [
    'number' => '4242424242424242',
    'expiryMonth' => '6',
    'expiryYear' => '2016',
    'cvv' => '123'
];

$response = $gateway->purchase([
    'createCard' => true, //optional - Will return tokenized card if included
    'amount' => '10.00',
    'currency' => 'USD',
    'transactionId' => '1234',
    'card' => $cardData,
    'testMode' => true //use for calls to FAC sandbox
])->send();

if ( $response->isSuccessful() ) {
    print_r($response);
}
else {
    echo $response->getMessage();
}

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Shamar Kellman](https://github.com/ShamarKellman)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
