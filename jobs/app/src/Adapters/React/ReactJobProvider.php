<?php

namespace Jobs\Adapters\React;

use Jobs\Job\Job;
use Jobs\Job\Provider;
use React\MySQL\ConnectionInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;

class ReactJobProvider implements Provider
{
    public function __construct(private ConnectionInterface $connection)
    {
    }

    public function provide(): PromiseInterface
    {
        return $this->connection
            ->query('SELECT `id`, `name`, `query`, `interval` FROM jobs')
            ->then(function (QueryResult $rows) {
                return $rows->resultRows;
            })
            ->then(function (array $rows) {
                return array_map(function(array $row) {
                    return new Job((int)$row['id'], $row['name'], $row['query'], (int)$row['interval']);
                }, $rows);
            });
    }
}
