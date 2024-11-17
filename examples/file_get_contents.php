<?php

require_once __DIR__.'/../vendor/autoload.php';

use KnotsPHP\PublicIP\Enums\HttpProvider;
use KnotsPHP\PublicIP\Fetchers\FileGetContentsFetcher;
use KnotsPHP\PublicIP\Finders\PublicIP;
use KnotsPHP\PublicIP\Finders\PublicIPv4;
use KnotsPHP\PublicIP\Finders\PublicIPv6;

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
