<?php
namespace Elevators\Request;

class RequestQueue
{
    /** @var int[] */
    private array $requests = [];

    public function add(int $level): void
    {
        $this->requests[] = $level;
    }

    public function get(): int
    {
        if ($this->empty()) {
            throw new \RuntimeException();
        }

        return (int)array_shift($this->requests);
    }

    public function empty(): bool
    {
        return count($this->requests) === 0;
    }
}
