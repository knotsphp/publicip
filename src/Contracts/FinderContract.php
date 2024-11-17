<?php

namespace KnotsPHP\PublicIP\Contracts;

use KnotsPHP\PublicIP\Enums\IpVersion;

interface FinderContract
{
    /**
     * @return array<FetcherContract>
     */
    public static function getDefaultFetchers(): array;

    public function resolveIpVersion(): ?IpVersion;

    public function __construct();
}
