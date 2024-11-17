<?php

namespace SRWieZ\Native\MyIP\Exceptions;

use SRWieZ\Native\MyIP\Enums\IpVersion;

class UnmatchedIpVersionReceived extends Exception
{
    public function __construct(?string $ip, IpVersion $ip_version)
    {
        parent::__construct("Asked for IP{$ip_version->name} address, but received {$ip}");
    }
}
