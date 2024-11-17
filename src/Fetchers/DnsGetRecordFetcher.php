<?php

namespace SRWieZ\Native\MyIP\Fetchers;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Exceptions\Exception;

// NOT RECOMMENDED: This fetcher uses the dns_get_record function
// which is blocking, slow and not reliable
// Also we can't set nameservers for the query nor use a timeout
class DnsGetRecordFetcher implements Fetcher
{
    /**
     * @var DnsProvider[]
     */
    public array $providers = [];

    /**
     * @return DnsProvider[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function from(DnsProvider $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    public function addProvider(DnsProvider $provider): self
    {
        return $this->from($provider);
    }

    public function all(): self
    {
        $this->providers = DnsProvider::cases();

        return $this;
    }

    public function onlyIPv4(): static
    {
        // Only Akamai has a dedicated IPv4 host
        $this->providers = [DnsProvider::Akamai];

        return $this;
    }

    public function onlyIPv6(): static
    {
        // Only Akamai has a dedicated IPv6 host
        $this->providers = [DnsProvider::Akamai];

        return $this;
    }

    public function fetch(?IpVersion $versionToResolve = null): ?string
    {
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
