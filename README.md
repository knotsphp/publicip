# native-myip

[//]: # ([![Latest Stable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Total Downloads]&#40;http://poser.pugx.org/srwiez/native-myip/downloads&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Latest Unstable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v/unstable&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![License]&#40;http://poser.pugx.org/srwiez/native-myip/license&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![PHP Version Require]&#40;http://poser.pugx.org/srwiez/native-myip/require/php&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41;)

[//]: # (![GitHub Workflow Status &#40;with event&#41;]&#40;https://img.shields.io/github/actions/workflow/status/srwiez/native-myip/test.yml?label=Tests&#41;)
A simple PHP library to get the public IP address of the current machine.

This library is designed to use dig, dns_get_record, or curl to obtain the public IP address of the current machine by
utilizing publicly available whoami services.

## 🚀 Installation

```bash
composer require srwiez/native-myip
```

## 📚 Usage

Easiest way to get the public IP address of the current machine is to use the `IPv4::get()` method.

```php
use SRWieZ\Native\MyIP\PublicIP;
use SRWieZ\Native\MyIP\PublicIPv4;
use SRWieZ\Native\MyIP\PublicIPv6;

$ip = PublicIP::get(); // returns either IPv4 or IPv6
$ipv4 = PublicIPv4::get(); // returns your IPv4
$ipv6 = PublicIPv6::get(); // returns your IPv6
```

[//]: # (Talk about the default configuration)

If you want to use a specific fetcher, or a specific provider, you can use the `IPv4::make()` method.

```php
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\PublicIPv4;

$ipv4 = IPv4::finder()
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

## 🏎️ Performance

If you are sure that your machine has `dig` installed, you can speed up the process by setting the `isSupported`
property to `true`.

It works both ways; if you are sure that your machine does not have `dig` installed, you can set it to `false` to
prevent unnecessary checks.

```php
use SRWieZ\Native\MyIP\Fetcher\DigFetcher;

DigFetcher::$isSupported = true;
```

## 📋 TODO

- PSR-18 HTTP fetcher
- curl fetcher
- A way to choose PSR-18 HTTP client so other tools can monitor outgoing requests
- Write tests
- Write documentation
- Publish v1
- Can we use this to get local network IP addresses?

## 📦 Alternatives

If you are not pleased with this library, check out the following alternatives:

- https://github.com/martinlindhe/php-myip

You can also contribute to this library by opening an issue or a pull request.

## 👥 Credits

native-myip was created by Eser DENIZ.

## 📝 License

native-myip is licensed under the MIT License. See LICENSE for more information.
