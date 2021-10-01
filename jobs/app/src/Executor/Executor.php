<?php

namespace Jobs\Executor;

use Jobs\HttpReporter\HttpReporter;
use Jobs\Job\Job;
use Jobs\QueryExecutor\QueryExecutor;
use Jobs\Stats\Collector;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Executor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private QueryExecutor $queryExecutor,
        private HttpReporter $httpReporter,
        private Collector $statsCollector,
    ) {}

    public function execute(Job $job): void
    {
        $this->logger->info('Execution of job started, querying data', ['job' => (string)$job]);

        $data = $this->queryExecutor->execute($job);

        $this->logger->info('Sending report to HTTP service', ['job' => (string)$job]);

        $this->httpReporter->report($job, $data);

        $this->logger->info('Collecting job stats', ['job' => (string)$job]);

        $this->statsCollector->collect($job);

        $this->logger->info('Execution of the job finished', ['job' => (string)$job]);
    }
}
