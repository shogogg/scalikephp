<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP\Support;

use ScalikePHP\ArraySeq;
use ScalikePHP\Seq;

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
    private $array;

    /**
     * @var \Closure
     */
    private $closure;

    /**
     * @var bool
     */
    private $computed = false;

    /**
     * Set the traversable.
     *
     * @param \Closure $closure
     * @return void
     */
    protected function setClosure(\Closure $closure): void
    {
        $this->closure = $closure;
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
    public function getIterator(): \Iterator
    {
        return call_user_func($this->closure);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function getRawIterable(): iterable
    {
        return $this->getIterator();
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
    public function toArray(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function toSeq(): Seq
    {
        return new ArraySeq($this->toArray());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    abstract protected function compute(): void;

}
