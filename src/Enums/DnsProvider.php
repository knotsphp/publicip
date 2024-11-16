<?php

namespace SRWieZ\Native\MyIP\Enums;

// dig whoami.akamai.net. @ns1-1.akamaitech.net. +short
// dig myip.opendns.com @resolver1.opendns.com +short
// dig whoami.cloudflare ch txt @1.1.1.1 +short
// dig TXT o-o.myaddr.l.google.com @ns1.google.com +short
enum DnsProvider
{
    case OpenDNS;
    case Cloudflare;
    case Google;
    case Akamai;

    public function getNameServer(): string
    {
        return match ($this) {
            DnsProvider::Akamai => 'ns1-1.akamaitech.net.',
            DnsProvider::Cloudflare => 'one.one.one.one.',
            DnsProvider::Google => 'ns1.google.com.',
            DnsProvider::OpenDNS => 'resolver1.opendns.com.',
        };
    }

    public function getIPv6Nameserver(): ?string
    {
        return match ($this) {
            DnsProvider::Cloudflare => '2606:4700:4700::1111',
            default => null,
        };
    }

    public function getIPv4Nameserver(): ?string
    {
        return match ($this) {
            DnsProvider::Cloudflare => '1.1.1.1',
            DnsProvider::Google => '8.8.8.8',
            default => null,
        };
    }

    public function getHost(): string
    {
        return match ($this) {
            DnsProvider::Akamai => 'whoami.akamai.net.',
            DnsProvider::Cloudflare => 'whoami.cloudflare.',
            DnsProvider::Google => 'o-o.myaddr.l.google.com.',
            DnsProvider::OpenDNS => 'myip.opendns.com.',
        };
    }

    public function getRecordType(): string
    {
        return match ($this) {
            DnsProvider::Akamai, DnsProvider::OpenDNS => 'A',
            DnsProvider::Cloudflare => 'CH TXT',
            DnsProvider::Google => 'TXT',
        };
    }

    /**
     * @return array<self>
     */
    public static function onlyIPv4(): array
    {
        return array_filter(self::cases(), fn (DnsProvider $provider) => $provider->getIPv4Nameserver() !== null);
    }

    /**
     * @return array<self>
     */
    public static function onlyIPv6(): array
    {
        return array_filter(self::cases(), fn (DnsProvider $provider) => $provider->getIPv6Nameserver() !== null);
    }
}
