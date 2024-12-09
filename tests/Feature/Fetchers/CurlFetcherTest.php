<?php

use KnotsPHP\PublicIP\Exceptions\NoProviderSpecified;
use KnotsPHP\PublicIP\Fetchers\CurlFetcher;

it('can fetch an ip address', function () {
    $ip = (new CurlFetcher)
        ->all()
        ->fetch();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

    expect($filtered)->toBeString();
})
    ->skip(
        conditionOrMessage: ! CurlFetcher::isSupported(),
        message: 'curl is not installed on this system.'
    );

it('throws an exception if no providers are set', function () {
    (new CurlFetcher)
        ->fetch();
})
    ->throws(NoProviderSpecified::class)
    ->skip(
        conditionOrMessage: ! CurlFetcher::isSupported(),
        message: 'curl is not installed on this system.'
    );
