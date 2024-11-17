<?php

namespace SRWieZ\Native\MyIP\Traits;

use SRWieZ\Native\MyIP\Enums\DnsProvider;

trait HasDnsProviders
{
    /**
     * @var DnsProvider[]
     */
    public array $providers = [];

    /**
     * @return DnsProvider[]
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function from(DnsProvider $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    public function addProvider(DnsProvider $provider): self
    {
        return $this->from($provider);
    }

    public function all(): self
    {
        $this->providers = DnsProvider::cases();

        return $this;
    }

    public function onlyIPv4(): self
    {
        $this->providers = DnsProvider::onlyIPv4();

        return $this;
    }

    public function onlyIPv6(): self
    {
        $this->providers = DnsProvider::onlyIPv6();

        return $this;
    }
}
