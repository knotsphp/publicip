<?php

namespace SRWieZ\Native\MyIP\Enums;

// dig whoami.akamai.net. @ns1-1.akamaitech.net. +short
// dig myip.opendns.com @resolver1.opendns.com +short
// dig whoami.cloudflare ch txt @1.1.1.1 +short
// dig TXT o-o.myaddr.l.google.com @ns1.google.com +short
enum DnsProvider
{
    case Cloudflare;
    case Google;
    case OpenDNS; // Could not work in France and Portugal 🥲
    case Akamai; // Pretty slow sometimes

    public function getNameServer(): ?string
    {
        return match ($this) {
            self::Akamai => null, // Works the other way around, uses Google nameservers to force IPv4 or IPv6
            self::Cloudflare => 'one.one.one.one.',
            self::Google => 'ns1.google.com.',
            self::OpenDNS => 'resolver1.opendns.com.',
        };
    }

    public function getIPv6Nameserver(): ?string // @phpstan-ignore return.unusedType
    {
        return match ($this) {
            self::Cloudflare => '2606:4700:4700::1111', // and 2606:4700:4700::1001
            self::OpenDNS => '2620:119:35::35', // and 2620:119:53::53
            self::Google, self::Akamai => '2001:4860:4860::8888', // and 2001:4860:4860::8844
        };
    }

    public function getIPv4Nameserver(): ?string // @phpstan-ignore return.unusedType
    {
        return match ($this) {
            self::Cloudflare => '1.1.1.1', // and 1.0.0.1
            self::OpenDNS => '208.67.222.222', // and 208.67.220.220
            self::Google, self::Akamai => '8.8.8.8', // and 8.8.4.4
        };
    }

    public function getHost(): string
    {
        return match ($this) {
            self::Akamai => 'whoami.ds.akahelp.net',
            self::Cloudflare => 'whoami.cloudflare.',
            self::Google => 'o-o.myaddr.l.google.com.',
            self::OpenDNS => 'myip.opendns.com.',
        };
    }

    public function getIPv4Host(): string
    {
        return match ($this) {
            self::Akamai => 'whoami.ipv4.akahelp.net',
            default => $this->getHost(),
        };
    }

    public function getIPv6Host(): string
    {
        return match ($this) {
            self::Akamai => 'whoami.ipv6.akahelp.net',
            default => $this->getHost(),
        };
    }

    public function getRecordType(): string
    {
        return match ($this) {
            self::OpenDNS => 'A',
            self::Cloudflare => 'CH TXT',
            self::Akamai, self::Google => 'TXT',
        };
    }

    /**
     * @return array<self>
     */
    public static function onlyIPv4(): array
    {
        return array_filter(self::cases(), fn (self $provider) => $provider->getIPv4Nameserver() !== null);
    }

    /**
     * @return array<self>
     */
    public static function onlyIPv6(): array
    {
        return array_filter(self::cases(), fn (self $provider) => $provider->getIPv6Nameserver() !== null);
    }

    public function parseDigResponse(string $response): ?string
    {
        if ($this === self::Akamai) {
            $lines = array_map(function ($line) {
                return str_getcsv($line, ' ');
            }, explode("\n", trim($response)));

            foreach ($lines as $line) {
                if (! empty($line[0]) && ! empty($line[1]) && $line[0] == 'ip') {
                    return $line[1];
                }
            }

            return null;
        }

        return trim($response, " \n\r\t\v\0\"");
    }
}
