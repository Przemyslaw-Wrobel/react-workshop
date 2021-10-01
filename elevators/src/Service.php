<?php
namespace Elevators;

class Service
{
    public function __construct(private string $name, private int $duration)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}
