<?php

namespace Jobs\Stats;

use Jobs\Job\Job;

class Collector
{
    public function __construct(
        private Storage $storage,
    ) {}

    public function collect(Job $job): void
    {
        $key = (string)$job;

        $data = ['lastExecution' => date('Y-m-d H:i:s')];

        $this->storage->store($key, $data);
    }
}
