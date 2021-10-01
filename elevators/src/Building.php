<?php
namespace Elevators;

class Building
{
    /** @var array<int, Floor> */
    public array $floors = [];

    public function __construct(private Elevators $elevators)
    {
        $this->addFloor([]);
    }

    /**
     * @param Service[] $services
     */
    public function addFloor(array $services): void
    {
        $level = count($this->floors);
        $this->floors[$level] = new Floor($level, $services);
    }

    public function addClient(Client $client): void
    {
        $this->floors[0]->addClient($client);
    }

    public function removeClient(Client $client): void
    {
        $this->floors[0]->removeClient($client);
    }

    public function findReadyElevator(int $level): ?Elevator
    {
        $elevator = $this->elevators->findReadyByLevel($level);

        if ($elevator === null) {
            $elevators = array_values($this->elevators->findReady());
            $elevator = count($elevators) ? $elevators[0] : null;
        }

        return $elevator;
    }

    /**
     * @param Elevator $elevator
     * @return Client[]
     */
    public function fillElevator(Elevator $elevator): array
    {
        $level = $elevator->getLevel();
        $clients = [];

        foreach ($this->floors[$level]->takeClients() as $client) {
            if ($client->isBusy()) {
                $this->floors[$level]->addClient($client);
                continue;
            }

            $elevator->addClient($client);
            $clients[] = $client;
        }

        return $clients;
    }

    /**
     * @param Client[] $clients
     * @return int
     */
    public function getMax(array $clients): int
    {
        $floors = array_map(fn (Client $client) => $this->findFloorForClient($client), $clients);

        return (int)max($floors);
    }

    public function findFloorForClient(Client $client): int
    {
        $floorLevel = 0;

        foreach ($this->floors as $level => $floor) {
            if (in_array($client->getService(), $floor->getServices())) {
                $floorLevel = $level;
                break;
            }
        }

        return $floorLevel;
    }

    /**
     * @param Elevator $elevator
     * @return Client[]
     */
    public function dispatchClients(Elevator $elevator): array
    {
        $dispatched = [];
        $floor = $this->floors[$elevator->getLevel()];

        foreach ($elevator->takeClients() as $client) {
            if (in_array($client->getService(), $floor->getServices(), true) || ($client->getService() === null && $elevator->getLevel() === 0)) {
                $dispatched[] = $client;
                $floor->addClient($client);
            } else {
                $elevator->addClient($client);
            }
        }

        return $dispatched;
    }

    /**
     * @return Service[]
     */
    public function collectServices(): array
    {
        $services = [];

        foreach ($this->floors as $floor) {
            $services = array_merge($services, $floor->getServices());
        }

        return $services;
    }
}
