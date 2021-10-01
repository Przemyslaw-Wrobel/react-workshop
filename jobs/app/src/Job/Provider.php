<?php
namespace Jobs\Job;

interface Provider
{
    /**
     * @return Job[]
     */
    public function provide(): array;
}
