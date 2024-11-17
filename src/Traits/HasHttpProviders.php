<?php

namespace KnotsPHP\PublicIP\Traits;

use KnotsPHP\PublicIP\Enums\HttpProvider;

trait HasHttpProviders
{
    /**
     * @var HttpProvider[]
     */
    public array $providers = [];

    /**
     * @return HttpProvider[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function from(HttpProvider $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    public function addProvider(HttpProvider $provider): self
    {
        return $this->from($provider);
    }

    public function all(): self
    {
        $this->providers = HttpProvider::cases();

        return $this;
    }

    public function onlyIPv4(): self
    {
        // Only Akamai has a dedicated IPv4 host
        $this->providers = HttpProvider::onlyIPv4();

        return $this;
    }

    public function onlyIPv6(): self
    {
        // Only Akamai has a dedicated IPv6 host
        $this->providers = HttpProvider::onlyIPv6();

        return $this;
    }
}
