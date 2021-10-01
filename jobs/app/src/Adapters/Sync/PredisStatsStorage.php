<?php

namespace Jobs\Adapters\Sync;

use Jobs\Stats\Storage;
use Predis\Client;

class PredisStatsStorage implements Storage
{
    public function __construct(
        private Client $client
    ) {}

    public function store(string $key, array $data): void
    {
        $this->client->set($key, json_encode($data));
    }
}
