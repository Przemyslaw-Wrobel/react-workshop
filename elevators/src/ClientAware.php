<?php

namespace Elevators;

trait ClientAware
{
    /** @var Client[] */
    private array $clients = [];

    public function addClient(Client $client): void
    {
        $this->clients[] = $client;
    }

    /**
     * @return Client[]
     */
    public function getClients(): array
    {
        return $this->clients;
    }

    public function hasClients(): bool
    {
        return !empty($this->clients);
    }

    public function removeClient(Client $client): void
    {
        $key = array_search($client, $this->clients);

        if ($key !== false) {
            array_splice($this->clients, $key, 1);
        }
    }

    /**
     * @return Client[]
     */
    public function takeClients(): array
    {
        $clients = $this->getClients();
        $this->clients = [];

        return $clients;
    }
}
