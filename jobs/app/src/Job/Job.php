<?php
namespace Jobs\Job;

class Job
{
    public function __construct(private int $id, private string $name, private string $query, private int $interval)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }

    public function __toString(): string
    {
        return sprintf('%s#%d', $this->name, $this->id);
    }

    public function context(): array
    {
        return ['job' => (string)$this, 'interval' => $this->interval];
    }
}
