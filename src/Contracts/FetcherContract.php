<?php

namespace KnotsPHP\PublicIP\Contracts;

use KnotsPHP\PublicIP\Enums\IpVersion;

interface FetcherContract
{
    public function fetch(?IpVersion $versionToResolve = null): ?string;

    public static function isSupported(): bool;
}
