<?php

namespace Jobs;

use Evenement\EventEmitterTrait;
use Jobs\Job\Job;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;

class Scheduler implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    use EventEmitterTrait;

    /** @var TimerInterface[] */
    private array $jobs = [];

    public function __construct(
      private LoopInterface $loop
    ) {}

    public function schedule(Job $job, int $interval): void
    {
        $this->jobs[$job->getId()] = $this->loop->addTimer($interval, function () use ($job) {
            $this->emit('ready', [$job]);
            unset($this->jobs[$job->getId()]);
        });

        $this->logger->debug(sprintf('Job scheduled in %d seconds', $interval), ['job' => (string)$job]);
    }
}
