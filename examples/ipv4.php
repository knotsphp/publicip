<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Finders\PublicIPv4;

echo PublicIPv4::get();
echo PHP_EOL;

// echo PublicIPv4::finder()
//     ->addFetcher((new DigFetcher)
//         ->from(DnsProvider::OpenDNS))
//     ->fetch();
// echo PHP_EOL;
