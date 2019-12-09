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
 * ScalikeTraversable implementation using an array.
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait ArraySupport
{

    /** @var array */
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

    /** {@inheritdoc} */
    public function count(): int
    {
        return count($this->array);
    }

    /** {@inheritdoc} */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->array);
    }

    /** {@inheritdoc} */
    protected function getRawIterable(): iterable
    {
        return $this->array;
    }

    /** {@inheritdoc} */
    public function isEmpty(): bool
    {
        return empty($this->array);
    }

    /** {@inheritdoc} */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /** {@inheritdoc} */
    public function offsetGet($offset)
    {
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    /** {@inheritdoc} */
    public function size(): int
    {
        return count($this->array);
    }

}
