<?php

namespace Jobs\QueryExecutor;

use Jobs\Job\Job;

interface QueryExecutor
{
    /**
     * @param Job $job
     * @return array
     */
    public function execute(Job $job): array;
}
