<?php

it('returns an ip', function () {
    exec('php cli/publicip.php', $output, $result_code);

    dump(trim($output[0]));
    $filtered = filter_var(trim($output[0]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

    expect($result_code)->toBe(0)
        ->and($filtered)->toBeString();
});

it('returns an ipv4', function () {
    exec('php cli/publicip.php --v4', $output, $result_code);

    $filtered = filter_var(trim($output[0]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

    expect($result_code)->toBe(0)
        ->and($filtered)->toBeString();
});

it('returns an ipv6', function () {
    exec('php cli/publicip.php --v6', $output, $result_code);

    $filtered = filter_var(trim($output[0]), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);

    expect($result_code)->toBe(0)
        ->and($filtered)->toBeString();
})->skip(
    conditionOrMessage: fn () => getenv('CI') === 'true',
    message: 'GitHub Actions does not have IPv6 enabled'
);
