<?php

require_once __DIR__.'/../vendor/autoload.php';

use KnotsPHP\PublicIP\Finders\PublicIP;

echo PublicIP::get();
echo PHP_EOL;

// DnsGetRecordFetcher SHOULD NOT BE USED, even when it works, the result is not reliable
//
// // DnsGetRecordFetcher IS NOT RECOMMENDED
// // If for whatever reason you still want to :
// // - Use it only with PublicIP and Akamai
// // - Impossible to choose the nameservers with dns_get_record
// // - Impossible to force ipv4 or ipv6 connection with dns_get_record
// // - Other providers than Akamai may return false IP
// echo PublicIP::finder()
//     ->addFetcher((new DnsGetRecordFetcher())
//         ->from(DnsProvider::Akamai))
//     ->fetch();
// echo PHP_EOL;
