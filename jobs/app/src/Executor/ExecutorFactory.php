<?php

namespace Jobs\Executor;

use Jobs\HttpReporter\HttpReporter;
use Jobs\QueryExecutor\QueryExecutorFactory;
use Jobs\Stats\Collector;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class ExecutorFactory implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private QueryExecutorFactory $queryExecutorFactory,
        private HttpReporter $httpReporter,
        private Collector $statsCollector,
    ) {}

    public function create(): Executor
    {
        $queryExecutor = $this->queryExecutorFactory->create();

        $executor = new Executor($queryExecutor, $this->httpReporter, $this->statsCollector);
        $executor->setLogger($this->logger);

        return $executor;
    }
}
