<?php

namespace Jobs\Adapters\Sync;

use Jobs\Job\Job;
use Jobs\QueryExecutor\QueryExecutor;

class PDOQueryExecutor implements QueryExecutor
{
    public function __construct(
        private \PDO $pdo
    ) {}

    public function execute(Job $job): array
    {
        $statement = $this->pdo->query($job->getQuery());

        return $statement->fetchAll();
    }
}
