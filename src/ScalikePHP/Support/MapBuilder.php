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
use InvalidArgumentException;
use ScalikePHP\Implementations\ArrayMap;
use ScalikePHP\Implementations\TraversableMap;
use ScalikePHP\Map;
use Traversable;

/**
 * Map building functions.
 */
trait MapBuilder
{
    private static ?Map $empty = null;

    /**
     * Create an instance from generator function.
     *
     * @param Closure $f
     *
     * @return \ScalikePHP\Map
     */
    final public static function create(Closure $f): Map
    {
        return Map::fromTraversable(new ClosureIterator($f));
    }

    /**
     * Get an empty Map instance.
     *
     * @return \ScalikePHP\Map
     */
    final public static function empty(): Map
    {
        if (self::$empty === null) {
            self::$empty = new ArrayMap([]);
        }
        return self::$empty;
    }

    /**
     * Get an empty Map instance.
     *
     * @return \ScalikePHP\Map
     * @deprecated Use `Map::empty()` instead.
     */
    public static function emptyMap(): Map
    {
        return Map::empty();
    }

    /**
     * Create a Map instance from an iterable.
     *
     * @param null|iterable $iterable
     * @throws InvalidArgumentException
     * @return \ScalikePHP\Map
     */
    final public static function from(?iterable $iterable): Map
    {
        if ($iterable === null) {
            return Map::empty();
        } elseif (is_array($iterable)) {
            return empty($iterable) ? Map::empty() : new ArrayMap((array)$iterable);
        } elseif ($iterable instanceof Traversable) {
            return Map::fromTraversable($iterable);
        } else {
            throw new InvalidArgumentException('Map::from() needs to array or \Traversable.');
        }
    }

    /**
     * Create an instance from an iterator.
     *
     * @param Traversable $traversable
     * @return \ScalikePHP\Map
     */
    final public static function fromTraversable(Traversable $traversable): Map
    {
        return new TraversableMap($traversable);
    }
}
