<?php

use KnotsPHP\PublicIP\Finders\PublicIP;
use KnotsPHP\PublicIP\Finders\PublicIPv4;
use KnotsPHP\PublicIP\Finders\PublicIPv6;

it('returns an ip address', function () {
    $ip = PublicIP::get();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

    expect($filtered)->toBeScalar();
});

it('returns an ipv4 address', function () {
    $ip = PublicIPv4::get();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

    expect($filtered)->toBeString();
});

it('returns an ipv6 address', function () {
    $ip = PublicIPv6::get();

    $filtered = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

    expect($filtered)->toBeString();
})->skip(
    conditionOrMessage: fn () => getenv('CI') === 'true',
    message: 'This test is skipped because the CI environment does not have an IPv6 address.'
);
