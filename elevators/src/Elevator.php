<?php
namespace Elevators;

class Elevator
{
    const UP = 'up';
    const DOWN = 'down';

    private int $level = 0;

    private int $speed = 1;

    private bool $moving = false;

    use ClientAware;

    public function __construct(private string $identifier)
    {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function move(string $direction): void
    {
        $this->level = $this->level + ($direction === Direction::UP ? 1 : -1);
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function isMoving(): bool
    {
        return $this->moving;
    }

    public function markMoving(bool $moving): void
    {
        $this->moving = $moving;
    }
}
