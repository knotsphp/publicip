<?php

use KnotsPHP\PublicIP\Enums\HttpProvider;
use KnotsPHP\PublicIP\Fetchers\CurlFetcher;

test('every http provider', function (HttpProvider $httpProvider) {

    $ip = (new CurlFetcher)
        ->addProvider($httpProvider)
        ->fetch();

    expect($ip)->not->toBeNull();

})
    ->with(HttpProvider::cases())
    ->skip(
        conditionOrMessage: ! CurlFetcher::isSupported(),
        message: 'curl is not installed on this system.'
    );
