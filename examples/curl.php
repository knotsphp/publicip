<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Enums\HttpProvider;
use SRWieZ\Native\MyIP\Fetchers\CurlFetcher;
use SRWieZ\Native\MyIP\Finders\PublicIP;
use SRWieZ\Native\MyIP\Finders\PublicIPv4;
use SRWieZ\Native\MyIP\Finders\PublicIPv6;

echo PublicIP::finder()
    ->addFetcher((new CurlFetcher)
        ->addProvider(HttpProvider::cloudflare)
        // ->all()
    )
    ->fetch();
echo PHP_EOL;

echo PublicIPv4::finder()
    ->addFetcher((new CurlFetcher)
        ->addProvider(HttpProvider::cloudflare)
        // ->onlyIPv4()
    )
    ->fetch();
echo PHP_EOL;

echo PublicIPv6::finder()
    ->addFetcher((new CurlFetcher)
        ->addProvider(HttpProvider::cloudflare)
        // ->onlyIPv6()
    )
    ->fetch();
echo PHP_EOL;
