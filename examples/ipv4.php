<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\PublicIPv4;

echo PublicIPv4::get();
echo PHP_EOL;

echo PublicIPv4::make()
    ->addFetcher((new DigFetcher)
        ->from(DnsProvider::Akamai))
    ->fetch();
echo PHP_EOL;
