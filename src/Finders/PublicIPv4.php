<?php

namespace KnotsPHP\PublicIP\Finders;

use KnotsPHP\PublicIP\Abstracts\Finder;
use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Enums\DnsProvider;
use KnotsPHP\PublicIP\Enums\HttpProvider;
use KnotsPHP\PublicIP\Enums\IpVersion;
use KnotsPHP\PublicIP\Fetchers\CurlFetcher;
use KnotsPHP\PublicIP\Fetchers\DigFetcher;

final class PublicIPv4 extends Finder
{
    /**
     * @return array<FetcherContract>
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
        return IpVersion::v4;
    }
}
