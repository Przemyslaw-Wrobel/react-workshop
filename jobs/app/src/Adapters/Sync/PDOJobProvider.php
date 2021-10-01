<?php

namespace Jobs\Adapters\Sync;

use Jobs\Job\Job;
use Jobs\Job\Provider;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class PDOJobProvider implements Provider
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function provide(): PromiseInterface
    {
        $query = 'SELECT `id`, `name`, `query`, `interval` FROM jobs';

        $rows = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        $jobs = array_map(fn (array $row) => new Job((int)$row['id'], $row['name'], $row['query'], (int)$row['interval']), $rows);

        return resolve($jobs);
    }
}
