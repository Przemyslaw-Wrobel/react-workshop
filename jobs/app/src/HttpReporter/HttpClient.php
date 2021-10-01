<?php

namespace Jobs\HttpReporter;

interface HttpClient
{
    /**
     * @param string $uri
     * @param array $postData
     * @return void
     */
    public function request(string $uri, array $postData): void;
}
