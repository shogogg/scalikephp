<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

use ScalikePHP\Support\CachingIterator;

/**
 * ScalikeTraversable implementation using an iterator(\Traversable).
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait TraversableSupport
{
    protected array $array;
    protected \Traversable $traversable;
    protected bool $computed = false;

    /**
     * Set the traversable.
     *
     * @param \Traversable $traversable
     */
    protected function setTraversable(\Traversable $traversable): void
    {
        $this->traversable = $traversable instanceof \Generator || $traversable instanceof \NoRewindIterator
            ? new CachingIterator($traversable)
            : $traversable;
    }

    // overrides
    public function count(): int
    {
        return count($this->toArray());
    }

    // overrides
    public function getIterator(): \Traversable
    {
        return $this->computed ? new \ArrayIterator($this->array) : $this->traversable;
    }

    // overrides
    protected function getRawIterable(): iterable
    {
        return $this->computed ? $this->array : $this->traversable;
    }

    // overrides
    public function isEmpty(): bool
    {
        return empty($this->toArray());
    }

    /**
     * PHP magic method: offsetExists.
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        $this->compute();
        return isset($this->array[$offset]);
    }

    /**
     * PHP magic method: offsetGet.
     *
     * @param $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $this->compute();
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    // overrides
    public function size(): int
    {
        return count($this->toArray());
    }

    /**
     * 遅延されている計算を行う.
     */
    abstract protected function compute(): void;
}
