
QuickBooks Payments PHP SDK
==========================
This SDK is designed to facilitate developers using QuickBooks Online Payments API. It provides a set of methods that make it easier to work with Intuit’s Payments API. It supports the following operations:

 - Standard OAuth 2.0 and OpenID Connect protocols
 - Interceptors for logging and error handling
 - Standard Payments API endpoint request

If you have not used with QuickBooks Online Payments API, please go to our docs at: https://developer.intuit.com/app/developer/qbpayments/docs/get-started

## Requirements

   1. PHP 7.0.0 and later.
   2. App for QuickBooks Online Payments API.


## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```php
   Not known yet
```

To use the bindings, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Manual Installation

If you do not wish to use Composer, you can download the [latest release](...). Then, to use the bindings, include the `init.php` file.

```php
require_once('/path/to/init.php');
```

## Dependencies

The bindings require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php), although you can use your own non-cURL client if you prefer
- [`json`](https://secure.php.net/manual/en/book.json.php)

If you specify the Guzzle client, then Guzzle is also required.

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make sure that these extensions are available.

## Getting Started

Simple usage looks like:

```php
$client = new PaymentClient([
  'access_token' : "your access token"
  'refresh_token' : "your refresh token"
]);

$array = [
  "amount" => "10.55",
  "currency" => "USD",
  "card" => [
      "name" => "emulate=0",
      "number" => "4111111111111111",
      "address" => [
        "streetAddress" => "1130 Kifer Rd",
        "city" => "Sunnyvale",
        "region" => "CA",
        "country" => "US",
        "postalCode" => "94086"
      ],
      "expMonth" => "02",
      "expYear" => "2020",
      "cvc" => "123"
  ],
  "context" => [
    "mobile" => "false",
    "isEcommerce" => "true"
  ]
];
$chargeBody = ChargeBuilder::buildFrom($array);
$chargeRequest = ChargeBuilder::createChargeRequest($chargeBody, $request_id);

$response = $client->send($chargeRequest);
```


## OAuth support

Developers can use this library to handle OAuth 2.0 protocol. It supports:
- Generating Authorization URL
- Getting OAuth2 Bearer Token
- Getting User Info
- Refreshing OAuth2 Token
- Revoking OAuth2 Token
- Migrating tokens from OAuth1.0 to OAuth2

Example:
```php

$oauth2Helper = OAuth2Authenticator::create([
  'client_id' => 'L0vmMZIfwUBfv9PPM96dzMTYATnLs6TSAe5SyVkt1Z4MAsvlCU',
  'client_secret' => '2ZZnCnnDyoZxUlVCP1D9X7khxA3zuXMyJE4cHXdq',
  'redirect_uri' => 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl',
  'environment' => 'development'
]);

$scope = "com.intuit.quickbooks.accounting openid profile email phone address";

//Generate Authorization URL Request.
$authorizationCodeURL = $oauth2Helper->generateAuthCodeURL($scope);

//Exchange code for token
$code = "someCode";
$request = $oauth2Helper->createRequestToExchange($code);

$client->send($request);
// use the Stripe API client as you normally would
```

## Interceptors

Developers can define their own interceptors to intercept request and response. THe default interceptor this SDK
provides is Logging interceptor.


```php
$client = new PaymentClient([
  'access_token' : ""
  'refresh_token' : ""
]);

$loggingInterceptor = new LoggingInterceptor();
$client->addInterceptor($loggingInterceptor);
```

Alternately, it is up to the developer to create their own interceptors to intercept request and response.

### Calling API endpoints.

The SDK supports multiple endpoints:
- Charge
- Token
- Card
- EChecks
- BankAccounts

For each endpoint, please refer to our documentation:
https://developer.intuit.com/app/developer/qbpayments/docs/api/resources/all-entities/bankaccounts

Example for creating charge:
```php

$client = new PaymentClient([
  'access_token' : ""
  'refresh_token' : ""
]);

$array = [
  "amount" => "10.55",
  "currency" => "USD",
  "card" => [
      "name" => "emulate=0",
      "number" => "4111111111111111",
      "address" => [
        "streetAddress" => "1130 Kifer Rd",
        "city" => "Sunnyvale",
        "region" => "CA",
        "country" => "US",
        "postalCode" => "94086"
      ],
      "expMonth" => "02",
      "expYear" => "2020",
      "cvc" => "123"
  ],
  "context" => [
    "mobile" => "false",
    "isEcommerce" => "true"
  ]
];
$chargeBody = ChargeBuilder::buildFrom($array);
$chargeRequest = ChargeBuilder::createChargeRequest($chargeBody, $request_id);

$response = $client->send($chargeRequest);
```

### Accessing response data

You can access the data from the last API response on any object via `getLastResponse()`.

```php
$charge = \Stripe\Charge::create(['amount' => 2000, 'currency' => 'usd', 'source' => 'tok_visa']);
echo $charge->getLastResponse()->headers['Request-Id'];
```

### SSL / TLS compatibility issues

QuickBooks Online API now requires that [all connections use TLS 1.2](https://developer.intuit.com/app/developer/homepage). Some systems (most notably some older CentOS and RHEL versions) are capable of using TLS 1.2 but will use TLS 1.0 or 1.1 by default. In this case, you'd get an `invalid_request_error`/

The recommended course of action is to [upgrade your cURL and OpenSSL packages](https://support.stripe.com/questions/how-do-i-upgrade-my-stripe-integration-from-tls-1-0-to-tls-1-2#php) so that TLS 1.2 is used by default, but if that is not possible, you might be able to solve the issue by setting the `CURLOPT_SSLVERSION` option to either `CURL_SSLVERSION_TLSv1` or `CURL_SSLVERSION_TLSv1_2`:
