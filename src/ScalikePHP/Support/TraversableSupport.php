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
 */
trait TraversableSupport
{

    /**
     * @var array
     */
    private $array;

    /**
     * @var \Traversable
     */
    private $traversable;

    /**
     * @var bool
     */
    private $computed = false;

    /**
     * Set the traversable.
     *
     * @param \Traversable $traversable
     * @return void
     */
    protected function setTraversable(\Traversable $traversable): void
    {
        $this->traversable = $traversable instanceof \Generator
            ? new GeneratorIterator($traversable)
            : $traversable;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::count()
     */
    public function count(): int
    {
        return count($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::getIterator()
     */
    public function getIterator(): \Iterator
    {
        if ($this->traversable instanceof \Iterator) {
            return $this->traversable;
        } elseif ($this->traversable instanceof \IteratorAggregate) {
            return $this->traversable->getIterator();
        } else {
            return new \ArrayIterator($this->toArray());
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::getRawIterable()
     */
    protected function getRawIterable(): iterable
    {
        return $this->traversable;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::isEmpty()
     */
    public function isEmpty(): bool
    {
        return empty($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        $this->compute();
        return isset($this->array[$offset]);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetGet()
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
     * @inheritdoc
     * @see ScalikeTraversable::size()
     */
    public function size(): int
    {
        return count($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toArray()
     */
    public function toArray(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toSeq()
     */
    public function toSeq(): Seq
    {
        return new ArraySeq($this->toArray());
    }

    /**
     * Compute values.
     *
     * @return void
     */
    private function compute(): void
    {
        if ($this->computed === false) {
            $this->array = iterator_to_array($this->traversable);
            $this->computed = true;
        }
    }

}
