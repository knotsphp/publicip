<?php

namespace KnotsPHP\PublicIP\Enums;

enum IpVersion: string
{
    case v4 = 'ipv4';
    case v6 = 'ipv6';
}
