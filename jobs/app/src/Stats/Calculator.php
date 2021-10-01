<?php

namespace Jobs\Stats;

use Jobs\Job\Job;

class Calculator
{
    public function calculate(Job $job): array
    {
        sleep(10);

        return ['count' => 1, 'id' => $job->getId()];
    }
}
