<?php

namespace Jobs\Adapters\Sync;

use Jobs\QueryExecutor\QueryExecutor;
use Jobs\QueryExecutor\QueryExecutorFactory;

class PDOQueryExecutorFactory implements QueryExecutorFactory
{
    public function __construct(
        private \PDO $pdo
    ) {}

    public function create(): QueryExecutor
    {
        return new PDOQueryExecutor($this->pdo);
    }
}
