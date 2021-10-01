<?php

namespace Jobs\Stats;

interface Storage
{
    public function store(string $key, array $data): void;
}
