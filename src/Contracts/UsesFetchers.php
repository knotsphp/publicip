<?php

namespace SRWieZ\Native\MyIP\Contracts;

use SRWieZ\Native\MyIP\Enums\IpVersion;

interface UsesFetchers
{
    /**
     * @return array<Fetcher>
     */
    public static function getDefaultFetchers(): array;

    public function resolveIpVersion(): ?IpVersion;
}
