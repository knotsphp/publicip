<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Finders\PublicIPv6;

echo PublicIPv6::get();
echo PHP_EOL;

// echo PublicIPv6::finder()
//     ->addFetcher((new DigFetcher)
//         ->from(DnsProvider::Cloudflare))
//     ->fetch();
// echo PHP_EOL;
