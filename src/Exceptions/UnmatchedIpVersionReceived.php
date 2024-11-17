<?php

namespace KnotsPHP\PublicIP\Exceptions;

use KnotsPHP\PublicIP\Enums\IpVersion;

class UnmatchedIpVersionReceived extends Exception
{
    public function __construct(?string $ip, IpVersion $ip_version)
    {
        parent::__construct("Asked for IP{$ip_version->name} address, but received {$ip}");
    }
}
