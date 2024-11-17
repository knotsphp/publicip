<?php

namespace SRWieZ\Native\MyIP;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Contracts\UsesFetchers;
use SRWieZ\Native\MyIP\Fetchers\DigFetcher;
use SRWieZ\Native\MyIP\Traits\HasFetchers;

final class PublicIP implements UsesFetchers
{
    use HasFetchers;

    public static function finder(): static
    {
        return new self;
    }

    /**
     * @return array<Fetcher>
     */
    public static function getDefaultFetchers(): array
    {
        return [
            (new DigFetcher)->all(),
        ];
    }

    public function resolveIpVersion(): null
    {
        return null;
    }
}
