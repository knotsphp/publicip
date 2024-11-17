<?php

namespace SRWieZ\Native\MyIP\Traits;

use SRWieZ\Native\MyIP\Contracts\Fetcher;
use SRWieZ\Native\MyIP\Enums\IpVersion;
use SRWieZ\Native\MyIP\Exceptions\IpAddressNotFound;
use SRWieZ\Native\MyIP\Exceptions\NoFetcherSpecified;
use SRWieZ\Native\MyIP\Exceptions\UnmatchedIpVersionReceived;

trait HasFetchers
{
    /**
     * @var Fetcher[]
     */
    protected array $fetchers = [];

    public function addFetcher(Fetcher $fetcher): static
    {
        $this->fetchers[] = $fetcher;

        return $this;
    }

    /**
     * @param  array<Fetcher>  $fetchers
     * @return $this
     */
    public function addFetchers(array $fetchers): static
    {
        $this->fetchers = array_merge($this->fetchers, $fetchers);

        return $this;
    }

    public static function with(?Fetcher $provider = null): static
    {
        $instance = new static;

        if ($provider) {
            $instance->addFetcher($provider);
        }

        return $instance;
    }

    /**
     * @throws IpAddressNotFound
     * @throws NoFetcherSpecified
     */
    public function fetch(): ?string
    {
        if (empty($this->fetchers)) {
            throw new NoFetcherSpecified;
        }

        $ip = null;
        $checkIpVersion = true;

        // Check is he has ip_version property
        $ip_version = $this->resolveIpVersion();

        foreach ($this->fetchers as $fetcher) {
            $ip = $fetcher->fetch($ip_version);
            $checkIpVersion = $this->checkIpVersion($ip, $ip_version);
            if ($ip && $checkIpVersion) {
                return $ip;
            }
        }

        // @phpstan-ignore-next-line identical.alwaysTrue
        if ($ip_version !== null && ! $checkIpVersion) {
            throw new UnmatchedIpVersionReceived($ip, $ip_version);
        }

        throw new IpAddressNotFound;
    }

    protected function checkIpVersion(?string $ip, ?IpVersion $ipVersion): bool
    {
        if (empty($ip)) {
            return true;
        }

        return match ($ipVersion) {
            IpVersion::v4 => filter_var($ip, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV4]) !== false,
            IpVersion::v6 => filter_var($ip, FILTER_VALIDATE_IP, ['flags' => FILTER_FLAG_IPV6]) !== false,
            default => true,
        };

    }

    public static function get(): ?string
    {
        /** @var array<Fetcher> $fetchers */
        $fetchers = array_filter(static::getDefaultFetchers(), function (Fetcher $fetcher) {
            return $fetcher::isSupported();
        });

        return (new static)
            ->addFetchers($fetchers)
            ->fetch();
    }
}
