<?php

namespace SRWieZ\Native\MyIP\Fetchers;

use SRWieZ\Native\MyIP\Contracts\FetcherContract;
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Traits\HasDnsProviders;

class DigFetcher implements FetcherContract
{
    use HasDnsProviders;

    public static bool $isSupported;

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
        $nameServer = match ($versionToResolve) {
            IpVersion::v4 => $provider->getIPv4Nameserver(),
            IpVersion::v6 => $provider->getIPv6NameServer(),
            default => $provider->getNameServer(),
        };

        $host = match ($versionToResolve) {
            IpVersion::v4 => $provider->getIPv4Host(),
            IpVersion::v6 => $provider->getIPv6Host(),
            default => $provider->getHost(),
        };

        // If the provider does not have a name server for the specified IP version, return null
        if (empty($nameServer) || empty($host)) {
            return null;
        }

        $cmd = sprintf('dig %s %s @%s +short', $provider->getRecordType(), $host, $nameServer);
        $response = shell_exec($cmd);

        if (empty($response)) {
            return null;
        }

        return $provider->parseDigResponse($response);
    }

    public static function isSupported(): bool
    {

        if (isset(self::$isSupported)) {
            return self::$isSupported;
        }

        // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $isSupported = ! empty(shell_exec('where dig'));
        } else {
            // Unix based
            $isSupported = ! empty(shell_exec('which dig'));
        }

        self::$isSupported = $isSupported;

        return $isSupported;
    }
}
