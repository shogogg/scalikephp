<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

use Closure;
use ScalikePHP\Option;

/**
 * Mutable map operations.
 *
 * @mixin \ScalikePHP\Map
 */
trait MutableMapOps
{
    /**
     * Returns associated value if given key is already in this map. Otherwise, computes value from given expression op,
     * stores with key in map and returns that value.
     *
     * @param int|string $key the key to test.
     * @param Closure $op a closure returns the value to associate with key, if key is previously unbound.
     * @return mixed the value associated with key (either previously or as a result of executing the method).
     * @noinspection PhpUnused
     */
    public function getOrElseUpdate($key, Closure $op)
    {
        return $this->get($key)->getOrElse(function () use ($key, $op) {
            $value = $op();
            $this->update($key, $value);
            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->update($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->array[$offset]);
    }

    /**
     * Removes a key from this map, returning the value associated previously with that key as an option.
     *
     * @param int|string $key the key to be removed.
     * @return \ScalikePHP\Option an option value containing the value associated previously with key,
     *                            or None if key was not defined in the map before.
     */
    public function remove($key): Option
    {
        if (isset($this->array[$key])) {
            $value = $this->array[$key];
            unset($this->array[$key]);
            return Option::some($value);
        } else {
            return Option::none();
        }
    }

    /**
     * Adds a new key/value pair to this map.
     * If the map already contains a mapping for the key, it will be overridden by the new value.
     *
     * @param int|string $key the key to update.
     * @param mixed $value the new value.
     */
    public function update($key, $value): void
    {
        $this->array[$key] = $value;
    }
}
