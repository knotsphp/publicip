<?php

namespace SRWieZ\Native\MyIP\Contracts;

use SRWieZ\Native\MyIP\Enums\IpVersion;

interface FetcherContract
{
    public function fetch(?IpVersion $versionToResolve = null): ?string;

    public static function isSupported(): bool;
}
