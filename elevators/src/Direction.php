<?php
namespace Elevators;

class Direction
{
    const UP = 'up';

    const DOWN = 'down';

    public static function reverse(string $direction): string
    {
        return $direction === self::UP ? self::DOWN : self::UP;
    }
}
