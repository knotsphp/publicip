<?php

it('returns an ip', function () {
    exec('php cli/publicip.php', $output, $result_code);

    expect($result_code)->toBe(0)
        ->and(filter_var($output[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6))->toBeString();
});

it('returns an ipv4', function () {
    exec('php cli/publicip.php --ipv4', $output, $result_code);

    expect($result_code)->toBe(0)
        ->and(filter_var($output[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))->toBeString();
});

it('returns an ipv6', function () {
    exec('php cli/publicip.php --ipv6', $output, $result_code);

    expect($result_code)->toBe(0)
        ->and(filter_var($output[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))->toBeString();
});
