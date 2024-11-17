<?php

namespace SRWieZ\Native\MyIP\Contracts;

use SRWieZ\Native\MyIP\Enums\IpVersion;

interface FinderContract
{
    /**
     * @return array<FetcherContract>
     */
    public static function getDefaultFetchers(): array;

    public function resolveIpVersion(): ?IpVersion;

    public function __construct();
}
