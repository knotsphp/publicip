<?php

namespace SRWieZ\Native\MyIP;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Contracts\UsesFetchers;
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\HttpProvider;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Fetchers\CurlFetcher;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\Traits\HasFetchers;

final class PublicIPv6 implements UsesFetchers
{
    use HasFetchers;

    public static function finder(): static
    {
        return new self;
    }

    /**
     * @return array<Fetcher>
     */
    public static function getDefaultFetchers(): array
    {
        return [
            (new DigFetcher)
                ->from(DnsProvider::Cloudflare)
                ->from(DnsProvider::OpenDNS),
            (new CurlFetcher)
                ->from(HttpProvider::ifconfigco)
                ->from(HttpProvider::ipifyorg)
                ->from(HttpProvider::ifconfigme)
                ->from(HttpProvider::cloudflare),
        ];
    }

    public function resolveIpVersion(): IpVersion
    {
        return IpVersion::v6;
    }
}
