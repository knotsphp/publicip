# native-myip

[//]: # ([![Latest Stable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Total Downloads]&#40;http://poser.pugx.org/srwiez/native-myip/downloads&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Latest Unstable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v/unstable&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![License]&#40;http://poser.pugx.org/srwiez/native-myip/license&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![PHP Version Require]&#40;http://poser.pugx.org/srwiez/native-myip/require/php&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41;)

[//]: # (![GitHub Workflow Status &#40;with event&#41;]&#40;https://img.shields.io/github/actions/workflow/status/srwiez/native-myip/test.yml?label=Tests&#41;)
A simple PHP library to get the public IP address of the current machine.

This library uses `dig` or HTTP requests to obtain the public IP address of the current machine by utilizing publicly available whoami services.

It comes with an opinionated default configuration to use the **fastest** and most **reliable** fetchers and providers. However, it also includes a flexible API that allows you to use different fetchers and different providers.

## 🚀 Installation

```bash
composer require srwiez/native-myip
```

## 📚 Usage

Easiest way to get the public IP address of the current machine is to use the `PublicIP::get()` method.

```php
use SRWieZ\Native\MyIP\Finders\PublicIP;use SRWieZ\Native\MyIP\Finders\PublicIPv4;use SRWieZ\Native\MyIP\Finders\PublicIPv6;

$ipv4 = PublicIPv4::get(); // returns your IPv4
$ipv6 = PublicIPv6::get(); // returns your IPv6
$ipv4or6 = PublicIP::get(); // returns either IPv4 or IPv6
```

[//]: # (Talk about the default configuration)

If you want to use a specific fetcher, or a specific provider, you can use the `IPv4::make()` method.

```php
use SRWieZ\Native\MyIP\Enums\DnsProvider;use SRWieZ\Native\MyIP\Fetchers\DigFetcher;use SRWieZ\Native\MyIP\Finders\PublicIPv4;

$ipv4 = PublicIPv4::finder()
    ->addFetcher((new DigFetcher())
        ->from(DnsProvider::Akamai)))
    ->fetch();
```

### Advanced Usage

You can also use a `Fetcher` directly to get the IP address.

```php
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;

$ipv4 = (new DigFetcher)->from(DnsProvider::Cloudflare)->fetch(IpVersion::v4);
```

Note that this returns null instead of throwing an exception if the fetcher fails.

## 🏃 Performance

If you are sure that your machine has `dig` installed, you can speed up the process by setting the `isSupported`
property to `true`.

It works both ways; if you are sure that your machine does not have `dig` installed, you can set it to `false` to
prevent unnecessary checks.

```php
use SRWieZ\Native\MyIP\Fetcher\DigFetcher;

DigFetcher::$isSupported = true;
```

If you use the `CurlFetcher`, you can set the `forceHTTP` property to `true` to use HTTP instead of HTTPS.
Some whoami services do not support HTTPS anyway as they are meant to be used in scripts like this `curl ifconfig.co`.

```php
use SRWieZ\Native\MyIP\Fetcher\CurlFetcher;

CurlFetcher::$forceHTTP = true;
```

## 📋 TODO

- PSR-18 HTTP fetcher with a way to choose psr compatible client so other tools can monitor outgoing requests
- Write tests
- Publish v1
- Look for a way to get local networks and IP addresses

## 📦 Alternatives

If you are not pleased with this library, check out the following alternatives:

- https://github.com/martinlindhe/php-myip

You can also contribute to this library by opening an issue or a pull request.

## 👥 Credits

native-myip was created by Eser DENIZ.

## 📝 License

native-myip is licensed under the MIT License. See LICENSE for more information.
