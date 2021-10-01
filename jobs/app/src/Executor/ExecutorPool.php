<?php

namespace Jobs\Executor;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class ExecutorPool implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private int $max = 2;

    /** @var Executor[] */
    private array $free = [];

    /** @var Executor[] */
    private array $occupied = [];

    /** @var callable[]  */
    private array $promises = [];

    public function __construct(
        private ExecutorFactory $factory
    ) {}

    /**
     * @return PromiseInterface<Executor>
     */
    public function get(): PromiseInterface
    {
        if ($this->available()) {
            return resolve($this->mine());
        }

        return new Promise(fn($resolve) => $this->promises[] = $resolve);
    }

    public function dispose(Executor $executor): void
    {
        if (!isset($this->occupied[spl_object_hash($executor)])) {
            return;
        }

        unset($this->occupied[spl_object_hash($executor)]);
        $this->free[] = $executor;

        if (!empty($this->promises)) {
            $promise = array_shift($this->promises);
            $promise($this->mine());
        }
    }

    private function mine(): Executor
    {
        if (!empty($this->available)) {
            $executor = array_shift($this->available);
        } else {
            $executor = $this->factory->create();
        }

        $this->occupied[spl_object_hash($executor)] = $executor;

        return $executor;
    }

    private function available(): bool
    {
        return !empty($this->available) || count($this->occupied) < $this->max;
    }
}
