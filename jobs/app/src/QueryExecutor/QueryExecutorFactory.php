<?php

namespace Jobs\QueryExecutor;

interface QueryExecutorFactory
{
    public function create(): QueryExecutor;
}
