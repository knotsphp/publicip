<?php

use KnotsPHP\PublicIP\Exceptions\NoProviderSpecified;
use KnotsPHP\PublicIP\Fetchers\DigFetcher;

it('can fetch an ip address', function () {
    $ip = (new DigFetcher)
        ->all()
        ->fetch();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

    expect($filtered)->toBeString();
})
    ->skip(
        conditionOrMessage: ! DigFetcher::isSupported(),
        message: 'curl is not installed on this system.'
    );

it('throws an exception if no providers are set', function () {
    (new DigFetcher)
        ->fetch();
})
    ->throws(NoProviderSpecified::class)
    ->skip(
        conditionOrMessage: ! DigFetcher::isSupported(),
        message: 'curl is not installed on this system.'
    );
