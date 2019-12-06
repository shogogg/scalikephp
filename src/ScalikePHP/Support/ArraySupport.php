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
 * ScalikeTraversable implementation using an array.
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait ArraySupport
{

    /**
     * @var array
     */
    private $array;

    /**
     * Set the array.
     *
     * @param mixed $array
     * @return void
     */
    protected function setArray(array $array): void
    {
        $this->array = $array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::count()
     */
    public function count(): int
    {
        return count($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::getRawIterable()
     */
    protected function getRawIterable(): iterable
    {
        return $this->array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::isEmpty()
     */
    public function isEmpty(): bool
    {
        return empty($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetGet()
     */
    public function offsetGet($offset)
    {
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
        return count($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toArray()
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toSeq()
     */
    public function toSeq(): Seq
    {
        return new ArraySeq($this->array);
    }

}
