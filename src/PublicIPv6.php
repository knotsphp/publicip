<?php

namespace SRWieZ\Native\MyIP;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Contracts\UsesFetchers;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\Traits\HasFetchers;

final class PublicIPv6 implements UsesFetchers
{
    use HasFetchers;

    public static function make(): static
    {
        return new self;
    }

    /**
     * @return array<Fetcher>
     */
    public static function getDefaultFetchers(): array
    {
        return [
            (new DigFetcher)->onlyIPv6(),
        ];
    }

    public function resolveIpVersion(): IpVersion
    {
        return IpVersion::v6;
    }
}
