<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use Closure;
use ScalikePHP\Support\MapBuilder;

/**
 * Scala like Map.
 */
abstract class Map extends ScalikeTraversable
{
    use MapBuilder;

    /**
     * Returns a new map containing the elements of this map followed by the given pair(s).
     *
     * @param array|Map|string $keyOrArray
     * @param mixed $value
     * @return static
     */
    abstract public function append($keyOrArray, $value = null): self;

    /**
     * Tests whether this map contains a binding for a key.
     *
     * @param int|string $key
     * @return bool
     */
    abstract public function contains($key): bool;

    /**
     * Optionally returns the value associated with a key.
     *
     * @param int|string $key the key value.
     * @return \ScalikePHP\Option an Option containing the value associated with key in this map,
     *                            or None if none exists.
     */
    abstract public function get($key): Option;

    /**
     * Returns the value associated with a key, or a default value if the key is not contained in the map.
     *
     * @param int|string $key the key value.
     * @param Closure $default a closure that returns a default value in case no binding for key is found in the map.
     * @return mixed the value associated with key if it exists, otherwise the result of the default computation.
     */
    abstract public function getOrElse($key, Closure $default);

    /**
     * Collects all keys of this map in a seq.
     *
     * @return \ScalikePHP\Seq the keys of this map as a seq.
     */
    abstract public function keys(): Seq;

    /**
     * Transforms this map by applying a function to every retrieved value.
     *
     * @param Closure $f the function used to transform values of this map.
     * @return \ScalikePHP\Map a map which maps every key of this map to `$f($this[$key])`.
     */
    abstract public function mapValues(Closure $f): self;

    /**
     * Converts this collection to an assoc.
     *
     * @return array
     */
    abstract public function toAssoc(): array;

    /**
     * Collects all values of this map in a seq.
     *
     * @return \ScalikePHP\Seq the values of this map as a seq.
     */
    abstract public function values(): Seq;
}
