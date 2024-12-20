#!/usr/bin/env php
<?php

use KnotsPHP\PublicIP\Finders\PublicIP;
use KnotsPHP\PublicIP\Finders\PublicIPv4;
use KnotsPHP\PublicIP\Finders\PublicIPv6;

error_reporting(E_ALL ^ E_DEPRECATED ^ E_NOTICE);

if (! class_exists('\Composer\InstalledVersions')) {
    require __DIR__.'/../vendor/autoload.php';
}

if (class_exists('\NunoMaduro\Collision\Provider')) {
    (new \NunoMaduro\Collision\Provider)->register();
}

$ipVersion = null;

// if --help or -h is passed, show help
if (in_array('--help', $argv) || in_array('-h', $argv)) {
    echo 'Usage: publicip [options]'.PHP_EOL;
    echo PHP_EOL;
    echo 'Options:'.PHP_EOL;
    echo '  --v4, -4  Use IPv4'.PHP_EOL;
    echo '  --v6, -6  Use IPv6'.PHP_EOL;
    echo '  --help, -h  Show this help'.PHP_EOL;
    exit(0);
}

// if --v4 or -4 is passed, use IPv4
if (in_array('--v4', $argv) || in_array('-4', $argv)) {
    $ipVersion = 'ipv4';
}

// if --v6 or -6 is passed, use IPv6
if (in_array('--v6', $argv) || in_array('-6', $argv)) {
    $ipVersion = 'ipv6';
}

$ip = match ($ipVersion) {
    'ipv4' => PublicIPv4::get(),
    'ipv6' => PublicIPv6::get(),
    default => PublicIP::get(),
};

if ($ip === null) {
    echo 'Could not find public IP address.'.PHP_EOL;
    exit(1);
}

echo $ip.PHP_EOL;
