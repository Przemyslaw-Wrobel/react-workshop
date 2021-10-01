<?php

namespace Jobs\QueryExecutor;

use Jobs\Job\Job;
use React\Promise\PromiseInterface;

interface QueryExecutor
{
    /**
     * @param Job $job
     * @return PromiseInterface<array>
     */
    public function execute(Job $job): PromiseInterface;
}
