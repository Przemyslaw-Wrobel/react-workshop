<?php
namespace Elevators;

class Elevators
{
    /**
     * @param Elevator[] $elevators
     */
    public function __construct(private array $elevators)
    {
    }

    public function findReadyByLevel(int $level): ?Elevator
    {
        $found = null;

        foreach ($this->elevators as $elevator) {
            if ($elevator->getLevel() === $level && !$elevator->isMoving()) {
                $found = $elevator;
                break;
            }
        }

        return $found;
    }

    /**
     * @return Elevator[]
     */
    public function findReady(): array
    {
        return array_filter($this->elevators, fn($elevator) => !$elevator->isMoving());
    }
}
