<?php

use SRWieZ\Native\MyIP\Contracts\FetcherContract;
use SRWieZ\Native\MyIP\Exceptions\Exception;

arch('enums')
    ->expect('SRWieZ\Native\MyIP\Enums')
    ->toBeEnums();

arch('traits')
    ->expect('SRWieZ\Native\MyIP\Traits')
    ->toBeTraits();

arch('contracts')
    ->expect('SRWieZ\Native\MyIP\Contracts')
    ->toBeInterfaces();

arch('exceptions')
    ->expect('SRWieZ\Native\MyIP\Exceptions')
    ->toExtend(Exception::class)
    ->ignoring(Exception::class);

arch('library.fetcher')
    ->expect('SRWieZ\Native\MyIP\Fetchers')
    ->toImplement(FetcherContract::class);

arch('library.finder')
    ->expect('SRWieZ\Native\MyIP\Finders')
    ->toExtend('SRWieZ\Native\MyIP\Abstracts\Finder')
    ->toBeFinal();

arch('debug')->preset()->php();
