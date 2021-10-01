<?php

namespace Jobs\Adapters\Sync;

use Jobs\Job\Job;
use Jobs\Job\Provider;

class PDOJobProvider implements Provider
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function provide(): array
    {
        $query = 'SELECT `id`, `name`, `query`, `interval` FROM jobs';

        $rows = $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn (array $row) => new Job((int)$row['id'], $row['name'], $row['query'], (int)$row['interval']), $rows);
    }
}
