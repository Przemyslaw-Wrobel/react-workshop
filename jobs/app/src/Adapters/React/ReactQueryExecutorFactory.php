<?php

namespace Jobs\Adapters\React;

use Jobs\QueryExecutor\QueryExecutor;
use Jobs\QueryExecutor\QueryExecutorFactory;
use React\MySQL\ConnectionInterface;

class ReactQueryExecutorFactory implements QueryExecutorFactory
{
    public function __construct(private ConnectionInterface $connection)
    {
    }

    public function create(): QueryExecutor
    {
        return new ReactQueryExecutor($this->connection);
    }
}
