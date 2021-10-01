<?php
namespace Elevators;

use React\EventLoop\LoopInterface;

class Runner
{
    /**
     * @param ClientHandler $handler
     * @param LoopInterface $loop
     */
    public function __construct(private ClientHandler $handler, private LoopInterface $loop)
    {
    }

    public function run(): void
    {
        $this->loop->run();
    }

    public function scheduleClient(Client $client, float $interval): void
    {
        $this->loop->addTimer($interval, function () use ($client) {
            $this->handler->handle($client);
        });
    }
}
