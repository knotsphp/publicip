<?php

namespace KnotsPHP\PublicIP\Fetchers;

use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Enums\DnsProvider;
use KnotsPHP\PublicIP\Enums\IpVersion;
use KnotsPHP\PublicIP\Exceptions\Exception;
use KnotsPHP\PublicIP\Exceptions\NoProviderSpecified;
use KnotsPHP\PublicIP\Traits\HasDnsProviders;

/**
 * NOT RECOMMENDED: This fetcher uses the dns_get_record function
 * which is blocking, slow and not reliable
 * Also we can't set nameservers for the query nor use a timeout
 *
 * Use it as EXPERIMENTAL ONLY
 *
 * @deprecated This class is not recommended for production use
 */
class DnsGetRecordFetcher implements FetcherContract
{
    use HasDnsProviders;

    public function onlyIPv4(): self
    {
        // Only Akamai has a dedicated IPv4 host
        $this->providers = [DnsProvider::Akamai];

        return $this;
    }

    public function onlyIPv6(): self
    {
        // Only Akamai has a dedicated IPv6 host
        $this->providers = [DnsProvider::Akamai];

        return $this;
    }

    /**
     * @throws Exception
     * @throws NoProviderSpecified
     */
    public function fetch(?IpVersion $versionToResolve = null): ?string
    {
        if (empty($this->providers)) {
            throw new NoProviderSpecified;
        }

        foreach ($this->providers as $provider) {
            $ip = $this->fetchFrom($provider, $versionToResolve);
            if ($ip) {
                return $ip;
            }
        }

        return null;
    }

    private function fetchFrom(DnsProvider $provider, ?IpVersion $versionToResolve): ?string
    {
        $host = match ($versionToResolve) {
            IpVersion::v4 => $provider->getIPv4Host(),
            IpVersion::v6 => $provider->getIPv6Host(),
            default => $provider->getHost(),
        };

        // If the provider does not have a name server for the specified IP version, return null
        if (empty($host)) {
            return null;
        }

        $type = match ($provider->getRecordType()) {
            'A' => DNS_A,
            'AAAA' => DNS_AAAA,
            'TXT' => DNS_TXT,
            default => throw new Exception(
                'Unsupported record type by dns_get_records : '.$provider->getRecordType()
            ),
        };

        $response = dns_get_record($host, $type);

        if (empty($response)) {
            return null;
        }

        // @phpstan-ignore-next-line argument.type
        $response = $this->transformToDigResponse($response);

        return $provider->parseDigResponse($response);
    }

    /**
     * @param  array<int, array{
     *     host: string,
     *     class: string,
     *     ttl: int,
     *     type: string,
     *     txt?: string,
     *     entries: array<int, string>
     * }> $response
     */
    private function transformToDigResponse(array $response): string
    {
        $return = '';
        foreach ($response as $record) {
            $return .= implode(' ', array_map(function (string $line) {
                return '"'.$line.'"';
            }, $record['entries']))."\n";
        }

        return $return;
    }

    public static function isSupported(): bool
    {
        // dns_get_record is available since PHP 5
        // we use composer to require PHP 8.3
        return true;
    }
}
