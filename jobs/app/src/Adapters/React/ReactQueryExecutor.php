<?php

namespace Jobs\Adapters\React;

use Jobs\Job\Job;
use Jobs\QueryExecutor\QueryExecutor;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class ReactQueryExecutor implements QueryExecutor
{
    public function __construct(private ConnectionInterface $connection)
    {
    }

    public function execute(Job $job): PromiseInterface
    {
        return $this->connection
            ->query($job->getQuery())
            ->then(fn(QueryResult $result) => $result->resultRows);
    }
}
