<?php

use KnotsPHP\PublicIP\Exceptions\NoProviderSpecified;
use KnotsPHP\PublicIP\Fetchers\FileGetContentsFetcher;

it('can fetch an ip address', function () {
    $ip = (new FileGetContentsFetcher)
        ->all()
        ->fetch();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

    expect($filtered)->toBeString();
});

it('throws an exception if no providers are set', function () {
    (new FileGetContentsFetcher)
        ->fetch();
})->throws(NoProviderSpecified::class);
