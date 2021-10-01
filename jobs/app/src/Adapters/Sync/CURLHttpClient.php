<?php

namespace Jobs\Adapters\Sync;

use Jobs\HttpReporter\HttpClient;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class CURLHttpClient implements HttpClient
{
    public function request(string $uri, array $postData): void
    {
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

        curl_exec($ch);
    }
}
