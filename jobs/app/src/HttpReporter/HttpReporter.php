<?php

namespace Jobs\HttpReporter;

use Jobs\Job\Job;

class HttpReporter
{
    public function __construct(
        private HttpClient $client,
    ) {}

    public function report(Job $job, array $data): void
    {
        $uri = sprintf('http://web?id=%d&interval=%d', $job->getId(), $job->getInterval());

        $this->client->request($uri, compact('data'));
    }
}
