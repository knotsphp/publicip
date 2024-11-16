<?php

namespace SRWieZ\Native\MyIP\Contracts;

use SRWieZ\Native\MyIP\Enums\IpVersion;

interface Fetcher
{
    public function fetch(?IpVersion $versionToResolve = null): ?string;

    public static function isSupported(): bool;
}
