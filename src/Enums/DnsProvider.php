<?php

namespace SRWieZ\Native\MyIP\Enums;

enum DnsProvider
{
    case Cloudflare;
    case Google; // Can't force IPv4 or IPv6
    case OpenDNS; // Could not work in France and Portugal 🥲
    case Akamai; // Sometimes slow to respond and random results

    public function getNameServer(): ?string
    {
        return match ($this) {
            self::Akamai => null, // Works the other way around, uses Google nameservers to force IPv4 or IPv6
            self::Cloudflare => 'one.one.one.one.',
            self::Google => 'ns1.google.com.',
            self::OpenDNS => 'resolver1.opendns.com.',
        };
    }

    public function getIPv6Nameserver(): ?string
    {
        return match ($this) {
            self::Cloudflare => '2606:4700:4700::1111', // and 2606:4700:4700::1001
            self::OpenDNS => '2620:119:35::35', // and 2620:119:53::53
            self::Akamai => '2001:4860:4860::8888', // and 2001:4860:4860::8844
            default => null,
        };
    }

    public function getIPv4Nameserver(): ?string
    {
        return match ($this) {
            self::Cloudflare => '1.1.1.1', // and 1.0.0.1
            self::OpenDNS => '208.67.222.222', // and 208.67.220.220
            self::Akamai => '8.8.8.8', // and 8.8.4.4
            default => null,
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

        if ($this === self::Google) {
            // remove the line starting by edns0-client-subnet
            $response = trim(implode('', array_filter(explode("\n", $response), function ($line) {
                return strpos($line, 'edns0-client-subnet') === false;
            })));
        }

        return trim($response, " \n\r\t\v\0\"");
    }
}
