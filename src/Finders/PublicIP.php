<?php

namespace SRWieZ\Native\MyIP\Finders;

use SRWieZ\Native\MyIP\Abstracts\Finder;
use SRWieZ\Native\MyIP\Contracts\FetcherContract;
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\HttpProvider;
use SRWieZ\Native\MyIP\Fetchers\CurlFetcher;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;

final class PublicIP extends Finder
{
    /**
     * @return array<FetcherContract>
     */
    public static function getDefaultFetchers(): array
    {
        return [
            (new DigFetcher)
                ->from(DnsProvider::Cloudflare)
                ->from(DnsProvider::Google)
                ->from(DnsProvider::OpenDNS),
            (new CurlFetcher)
                ->from(HttpProvider::ifconfigco)
                ->from(HttpProvider::ipifyorg)
                ->from(HttpProvider::ifconfigme)
                ->from(HttpProvider::cloudflare),
        ];
    }

    public function resolveIpVersion(): null
    {
        return null;
    }
}
