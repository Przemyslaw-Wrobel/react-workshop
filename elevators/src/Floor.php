<?php
namespace Elevators;

class Floor
{
    use ClientAware;

    private int $level;

    /** @var Service[]  */
    private array $services = [];

    /** @var Client[] */
    private array $clients = [];

    /**
     * @param Service[] $services
     */
    public function __construct(int $level, array $services)
    {
        $this->level = $level;
        $this->services = $services;
    }

    public function hasService(Service $service): bool
    {
        return in_array($service, $this->services, true);
    }

    /**
     * @return Service[]
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
