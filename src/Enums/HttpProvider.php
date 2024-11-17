<?php

namespace KnotsPHP\PublicIP\Enums;

enum HttpProvider: string
{
    case ifconfigco = 'ifconfig.co'; // Protocol + HTTPS (Fast)
    case ipifyorg = 'api64.ipify.org'; // Protocol + HTTPS + Urls (Open Source)
    case ifconfigme = 'ifconfig.me'; // Protocol + HTTPS
    case icanhazipcom = 'icanhazip.com'; // Protocol + HTTPS
    case cloudflare = 'cloudflare.com'; // Protocol + HTTPS (Use more data but still fast)
    case ipinfoio = 'ipinfo.io'; // HTTPS + Only IPv4
    case amazonaws = 'checkip.amazonaws.com'; // HTTPS + Only IPv4 (not reliable)

    public function getUrl(): string
    {
        return match ($this) {
            self::ipinfoio => 'ipinfo.io/ip',
            self::cloudflare => 'cloudflare.com/cdn-cgi/trace',
            default => $this->value,
        };
    }

    /**
     * Returns only cases that support asking for a specific IP version
     *
     * @return array<self>
     */
    public static function onlyIPv4(): array
    {
        return array_filter(self::cases(), fn (self $provider) => $provider->supportsBothIpVersion());
    }

    /**
     * Returns only cases that support asking for a specific IP version
     *
     * @return array<self>
     */
    public static function onlyIPv6(): array
    {
        return array_filter(self::cases(), fn (self $provider) => $provider->supportsBothIpVersion());
    }

    public function getIPv4Url(): ?string
    {
        return match ($this) {
            self::ipifyorg => 'api.ipify.org',
            default => null,
        };
    }

    public function getIPv6Url(): ?string
    {
        return match ($this) {
            self::ipifyorg => 'api6.ipify.org',
            default => null,
        };
    }

    public function getProtocol(): string
    {
        return $this->supportsSSL() ? 'https://' : 'http://';
    }

    public function supportsSSL(): bool
    {
        return true;
    }

    // Supports by forcing the IP protocol with curl
    public function supportsBothIpVersion(): bool
    {
        return match ($this) {
            self::amazonaws => false,
            self::ipinfoio => false,
            default => true,
        };
    }

    public function parseResponse(string $response): string
    {
        if ($this === self::cloudflare) {
            $lines = array_filter(explode("\n", trim($response)), function ($line) {
                return str_starts_with($line, 'ip=');
            });

            if (empty($lines)) {
                return '';
            }

            $ip = array_shift($lines);

            $response = str_replace('ip=', '', $ip);
        }

        return trim($response, " \n\r\t\v\0\"");
    }
}
