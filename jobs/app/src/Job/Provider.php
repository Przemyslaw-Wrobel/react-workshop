<?php
namespace Jobs\Job;

use React\Promise\PromiseInterface;

interface Provider
{
    /**
     * @return PromiseInterface<Job[]>
     */
    public function provide(): PromiseInterface;
}
