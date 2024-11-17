<?php

namespace KnotsPHP\PublicIP\Fetchers;

use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Enums\HttpProvider;
use KnotsPHP\PublicIP\Enums\IpVersion;
use KnotsPHP\PublicIP\Traits\HasHttpProviders;

class FileGetContentsFetcher implements FetcherContract
{
    use HasHttpProviders;

    public static bool $forceHTTP = false;

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

    private function fetchFrom(HttpProvider $provider, ?IpVersion $versionToResolve): ?string
    {
        // Either it has a specific host for the IP version or it supports both
        if (! is_null($versionToResolve) && $provider->supportsBothIpVersion()) {
            $host = match ($versionToResolve) {
                IpVersion::v4 => $provider->getIPv4Url() ?? $provider->getUrl(),
                IpVersion::v6 => $provider->getIPv6Url() ?? $provider->getUrl(),
            };
        } else {
            $host = $provider->getUrl();
        }

        $protocol = self::$forceHTTP ? 'http://' : $provider->getProtocol();
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'Accept: text/plain',
            ],
        ];
        // Force IPv4 or IPv6
        if ($versionToResolve === IpVersion::v4) {
            $opts['socket'] = ['bindto' => '0:0'];
        } elseif ($versionToResolve === IpVersion::v6) {
            $opts['socket'] = ['bindto' => '[::]:0'];
        }
        $context = stream_context_create($opts);

        $response = file_get_contents($protocol.$host, false, $context);

        if (empty($response)) {
            return null;
        }

        return $provider->parseResponse((string) $response);
    }

    public static function isSupported(): bool
    {
        // file_get_contents exists since PHP 4, no need to check
        return true;
    }
}
