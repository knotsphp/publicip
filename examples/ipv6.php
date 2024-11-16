<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\PublicIPv6;

echo PublicIPv6::get();
echo PHP_EOL;

echo PublicIPv6::make()
    ->addFetcher((new DigFetcher)
        ->from(DnsProvider::Akamai))
    ->fetch();
echo PHP_EOL;
