<?php

namespace KnotsPHP\PublicIP\Fetchers;

use KnotsPHP\PublicIP\Contracts\FetcherContract;
use KnotsPHP\PublicIP\Enums\HttpProvider;
use KnotsPHP\PublicIP\Enums\IpVersion;
use KnotsPHP\PublicIP\Traits\HasHttpProviders;

class CurlFetcher implements FetcherContract
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
        $ch = curl_init($protocol.$host);

        // Force IPv4 or IPv6
        if ($versionToResolve === IpVersion::v4) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        } elseif ($versionToResolve === IpVersion::v6) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V6);
        }

        // Return the response instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Small timeout to prevent blocking
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);

        // Follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // Accept only text
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: text/plain']);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            return null;
        }

        return $provider->parseResponse((string) $response);
    }

    public static function isSupported(): bool
    {
        // check if curl is enabled
        return function_exists('curl_version');
    }
}
