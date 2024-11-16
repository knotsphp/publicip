<?php

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Exceptions\Exception;

arch()
    ->expect('\SRWieZ\Native\MyIP\Fetchers')
    ->toImplement(Fetcher::class);

arch()
    ->expect('\SRWieZ\Native\MyIP\Exceptions')
    ->toExtend(Exception::class)
    ->ignoring(Exception::class);

arch()
    ->expect('\SRWieZ\Native\MyIP\Enums')
    ->toBeEnums();

arch()->preset()->php();
