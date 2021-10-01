<?php
namespace Elevators;

use Elevators\Request\RequestQueue;
use React\EventLoop\LoopInterface;

class ClientHandler
{
    private RequestQueue $requests;

    public function __construct(private Building $building, private LoopInterface $loop)
    {
        $this->requests = new RequestQueue();
    }

    public function handle(Client $client): void
    {
        $this->building->addClient($client);

        $this->log('The client %s entered the building', $client->getIdentifier());

        $this->lookForElevator(0);
    }

    private function lookForElevator(int $floor): void
    {
        $elevator = $this->building->findReadyElevator($floor);

        /**
         * The elevator is ready on our floor
         */
        if ($elevator !== null && $elevator->getLevel() === $floor) {
            $this->bringAndMove($elevator);
        }
        /**
         * We have to call the elevator
         */
        else {
            $this->requests->add($floor);

            if ($elevator !== null) {
                $this->checkRequests($elevator);
            }
        }
    }

    /**
     * Puts people who are going in the same direction into the elevator
     */
    private function putClientsIn(Elevator $elevator): bool
    {
        $clients = $this->building->fillElevator($elevator);

        foreach ($clients as $client) {
            $this->log('The client %s entered the elevator %s at floor %d', $client->getIdentifier(), $elevator->getIdentifier(), $elevator->getLevel());
        }

        return count($clients) > 0;
    }

    private function bringAndMove(Elevator $elevator): bool
    {
        if ($elevator->isMoving()) {
            return false;
        }

        if ($this->putClientsIn($elevator)) {
            $targetFloor = $this->building->getMax($elevator->getClients());
            $this->move($elevator, $targetFloor);
            return true;
        }

        return false;
    }

    private function move(Elevator $elevator, int $targetFloor = null): void
    {
        $direction = $elevator->getLevel() > $targetFloor ? Direction::DOWN : Direction::UP;

        $speed = $elevator->getSpeed();
        $elevator->markMoving(true);

        $this->loop->addPeriodicTimer($speed, function ($timer) use ($elevator, $direction, $targetFloor) {
            $elevator->move($direction);

            $this->log('The elevator %s arrived at floor %d', $elevator->getIdentifier(), $elevator->getLevel());

            $this->dispatchClients($elevator);

            if ($elevator->getLevel() === $targetFloor) {
                $this->loop->cancelTimer($timer);
                $elevator->markMoving(false);

                $this->bringAndMove($elevator) || $this->checkRequests($elevator);
            }
        });
    }

    private function dispatchClients(Elevator $elevator): void
    {
        $level = $elevator->getLevel();
        $clients = $this->building->dispatchClients($elevator);

        foreach ($clients as $client) {
            $this->log('The client %s has left the elevator %s at floor %d', $client->getIdentifier(), $elevator->getIdentifier(), $level);

            if ($client->getService() === null) {
                $this->building->removeClient($client);
                $this->log('The client %s has left the building', $client->getIdentifier());
            } else {
                $this->handleClientService($client, $level);
            }
        }
    }

    private function handleClientService(Client $client, int $level): void
    {
        $service = $client->handle();

        $this->log('The client %s is served by %s', $client->getIdentifier(), $service->getName());

        $this->loop->addTimer($service->getDuration(), function () use ($client, $level) {
            $this->log('The client %s is ready and looks for the elevator on floor %d', $client->getIdentifier(), $level);

            $client->release();

            $this->lookForElevator($level);
        });
    }

    private function checkRequests(Elevator $elevator): void
    {
        if ($this->requests->empty()) {
            return;
        }

        $floor = $this->requests->get();

        $this->move($elevator, $floor);
    }

    /**
     * @param string $message
     * @param string|int ...$l
     */
    private function log(string $message, ...$l): void
    {
        $timestamp = date('H:i:s');
        echo sprintf("[$timestamp] $message" . PHP_EOL, ...$l);
    }
}
