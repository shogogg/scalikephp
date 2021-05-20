<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

use ArrayIterator;
use OutOfBoundsException;
use Traversable;

/**
 * ScalikeTraversable implementation using an array.
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait ArraySupport
{
    private array $array;

    /**
     * @param mixed $array
     */
    protected function setArray(array $array): void
    {
        $this->array = $array;
    }

    // overrides
    public function count(): int
    {
        return count($this->array);
    }

    // overrides
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->array);
    }

    // overrides
    protected function getRawIterable(): iterable
    {
        return $this->array;
    }

    // overrides
    public function isEmpty(): bool
    {
        return empty($this->array);
    }

    /**
     * PHP magic method: offsetExists.
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * PHP magic method: offsetGet.
     *
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            throw new OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    // overrides
    public function size(): int
    {
        return count($this->array);
    }
}
