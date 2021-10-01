<?php

namespace Jobs;

use Jobs\Executor\Executor;
use Jobs\Job\Job;
use Jobs\Job\Provider;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class App implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private Provider $provider,
        private Scheduler $scheduler,
        private Executor $executor
    ) {}

    public function run(): void
    {
        $this->logger->info('Loading jobs');

        $jobs = $this->provider->provide();

        $this->logger->info('Jobs loaded', ['jobs' => count($jobs)]);

        foreach ($jobs as $job) {
            $this->scheduler->schedule($job, 1);
        }

        $this->scheduler->on('ready', fn(Job $job) => $this->handleJob($job));
    }

    private function handleJob(Job $job): void
    {
        $this->executor->execute($job);
        $this->scheduler->schedule($job, $job->getInterval());
    }
}
