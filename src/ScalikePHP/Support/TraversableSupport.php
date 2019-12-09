<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

/**
 * ScalikeTraversable implementation using an iterator(\Traversable).
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait TraversableSupport
{

    /**
     * @var array
     */
    protected $array;

    /**
     * @var \Traversable
     */
    protected $traversable;

    /**
     * @var bool
     */
    protected $computed = false;

    /**
     * Set the traversable.
     *
     * @param \Traversable $traversable
     * @return void
     */
    protected function setTraversable(\Traversable $traversable): void
    {
        $this->traversable = $traversable instanceof \Generator || $traversable instanceof \NoRewindIterator
            ? new CachingIterator($traversable)
            : $traversable;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function count(): int
    {
        return count($this->toArray());
    }

    /** {@inheritdoc} */
    public function getIterator(): \Traversable
    {
        return $this->computed ? new \ArrayIterator($this->array) : $this->traversable;
    }

    /** {@inheritdoc} */
    protected function getRawIterable(): iterable
    {
        return $this->computed ? $this->array : $this->traversable;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function isEmpty(): bool
    {
        return empty($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function offsetExists($offset): bool
    {
        $this->compute();
        return isset($this->array[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function offsetGet($offset)
    {
        $this->compute();
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function size(): int
    {
        return count($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    abstract protected function compute(): void;

}
