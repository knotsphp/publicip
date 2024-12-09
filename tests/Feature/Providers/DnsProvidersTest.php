<?php

use KnotsPHP\PublicIP\Enums\DnsProvider;
use KnotsPHP\PublicIP\Fetchers\DigFetcher;

test('every dns provider', function (DnsProvider $dnsProvider) {

    $ip = (new DigFetcher)
        ->addProvider($dnsProvider)
        ->fetch();

    expect($ip)->not->toBeNull();

})
    ->with(DnsProvider::cases())
    // ->skip('this test is not important for now')
    ->skip(
        conditionOrMessage: ! DigFetcher::isSupported(),
        message: 'dig is not installed on this system.'
    );
