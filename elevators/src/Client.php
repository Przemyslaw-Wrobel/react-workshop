<?php
namespace Elevators;

class Client
{
    private bool $busy = false;

    private ?Service $service;

    public function __construct(private string $identifier, Service $service)
    {
        $this->service = $service;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function isBusy(): bool
    {
        return $this->busy;
    }

    public function handle(): Service
    {
        $this->busy = true;
        $service = $this->service;
        $this->service = null;

        return $service;
    }

    public function release(): void
    {
        $this->busy = false;
    }
}
