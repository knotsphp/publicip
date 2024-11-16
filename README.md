# native-myip

[//]: # ([![Latest Stable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Total Downloads]&#40;http://poser.pugx.org/srwiez/native-myip/downloads&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![Latest Unstable Version]&#40;http://poser.pugx.org/srwiez/native-myip/v/unstable&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![License]&#40;http://poser.pugx.org/srwiez/native-myip/license&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41; [![PHP Version Require]&#40;http://poser.pugx.org/srwiez/native-myip/require/php&#41;]&#40;https://packagist.org/packages/srwiez/native-myip&#41;)

[//]: # (![GitHub Workflow Status &#40;with event&#41;]&#40;https://img.shields.io/github/actions/workflow/status/srwiez/native-myip/test.yml?label=Tests&#41;)

A simple PHP library to get the public IP address of the current machine.

## 🚀 Installation

```bash
composer require srwiez/native-myip
```

## 📚 Usage

Easiest way to get the public IP address of the current machine is to use the `IPv4::get()` method.

```php
$ipv4 = \SRWieZ\Native\MyIP\PublicIPv4::get();
```

If you want to control the priorities
```php
use \SRWieZ\Native\MyIP\Fetcher\DigFetcher;
use \SRWieZ\Native\MyIP\IPv4;

$ipv4 = IPv4::make()
    ->addFetcher(new DigFetcher::all())
    ->fetch();
```

### 🏎️ Performance

If you are sure that your machine has `dig` installed, you can speed up the process by setting the `isSupported` property to `true`.
Works both ways, if you are sure that your machine does not have `dig` installed, you can set it to `false` to prevent unnecessary checks.

```php
use \SRWieZ\Native\MyIP\Fetcher\DigFetcher;

DigFetcher::$isSupported = true;
```

### 📋 TODO

- [ ] HTTP fetcher
- [ ] dns_get_record fetcher
- [ ] curl fetcher
- [ ] A way to choose PSR-18 HTTP client so other tools can monitor outgoing requests
- [ ] Write tests
- [ ] Write documentation
- [ ] Test on different platforms
- [ ] Publish v1

## 👥 Credits

native-myip was created by Eser DENIZ.

## 📝 License

native-myip is licensed under the MIT License. See LICENSE for more information.