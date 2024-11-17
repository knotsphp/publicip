<?php

require_once __DIR__.'/../vendor/autoload.php';

use SRWieZ\Native\MyIP\Enums\HttpProvider;
use SRWieZ\Native\MyIP\Fetchers\FileGetContentsFetcher;
use SRWieZ\Native\MyIP\Finders\PublicIP;
use SRWieZ\Native\MyIP\Finders\PublicIPv4;
use SRWieZ\Native\MyIP\Finders\PublicIPv6;

echo PublicIP::finder()
    ->addFetcher((new FileGetContentsFetcher)
        ->addProvider(HttpProvider::cloudflare)
    )
    ->fetch();
echo PHP_EOL;

echo PublicIPv4::finder()
    ->addFetcher((new FileGetContentsFetcher)
        ->addProvider(HttpProvider::cloudflare)
    )
    ->fetch();
echo PHP_EOL;

echo PublicIPv6::finder()
    ->addFetcher((new FileGetContentsFetcher)
        ->addProvider(HttpProvider::cloudflare)
    )
    ->fetch();
echo PHP_EOL;
