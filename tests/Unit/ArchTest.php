<?php

use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Exceptions\Exception;

arch('enums')
    ->expect('KnotsPHP\PublicIP\Enums')
    ->toBeEnums();

arch('traits')
    ->expect('KnotsPHP\PublicIP\Traits')
    ->toBeTraits();

arch('contracts')
    ->expect('KnotsPHP\PublicIP\Contracts')
    ->toBeInterfaces();

arch('exceptions')
    ->expect('KnotsPHP\PublicIP\Exceptions')
    ->toExtend(Exception::class)
    ->ignoring(Exception::class);

arch('library.fetcher')
    ->expect('KnotsPHP\PublicIP\Fetchers')
    ->toImplement(FetcherContract::class);

arch('library.finder')
    ->expect('KnotsPHP\PublicIP\Finders')
    ->toExtend('KnotsPHP\PublicIP\Abstracts\Finder')
    ->toBeFinal();

arch('debug')->preset()->php();
