<?php

namespace SRWieZ\Native\MyIP\Fetchers;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Enums\DnsProvider;
use SRWieZ\Native\MyIP\Enums\IpVersion;

class DigFetcher implements Fetcher
{
    public static bool $isSupported;

    /**
     * @var DnsProvider[]
     */
    public static array $providers = [];

    /**
     * @return DnsProvider[]
     */
    public function getProviders(): array
    {
        return static::$providers;
    }

    public function from(DnsProvider $provider): self
    {
        static::$providers[] = $provider;

        return $this;
    }

    public function all(): self
    {
        static::$providers = DnsProvider::cases();

        return $this;
    }

    public function onlyIPv4(): static
    {
        static::$providers = DnsProvider::onlyIPv4();

        return $this;
    }

    public function onlyIPv6(): static
    {
        static::$providers = DnsProvider::onlyIPv6();

        return $this;
    }

    public function fetch(?IpVersion $versionToResolve = null): ?string
    {

        foreach (static::$providers as $provider) {
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

        return $provider->parseResponse($response);
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
