<?php

namespace KnotsPHP\PublicIP\Fetchers;

use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Enums\DnsProvider;
use KnotsPHP\PublicIP\Enums\IpVersion;
use KnotsPHP\PublicIP\Exceptions\NoProviderSpecified;
use KnotsPHP\PublicIP\Traits\HasDnsProviders;

class DigFetcher implements FetcherContract
{
    use HasDnsProviders;

    public static bool $isSupported;

    protected int $timeout = 1;

    /**
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

        $cmd = sprintf('dig %s %s @%s +short +time=%s', $provider->getRecordType(), $host, $nameServer, $this->timeout);
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

    public function setTimeout(int $timeout): self
    {
        if ($timeout < 1) {
            throw new \InvalidArgumentException('Timeout must be greater than 0');
        }

        $this->timeout = $timeout;

        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
