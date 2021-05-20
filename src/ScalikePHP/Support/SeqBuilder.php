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
use ScalikePHP\ArraySeq;
use ScalikePHP\Seq;
use ScalikePHP\TraversableSeq;
use Traversable;

/**
 * Seq building functions.
 */
trait SeqBuilder
{
    private static ?Seq $empty = null;

    /**
     * Create an instance from generator function.
     *
     * @param Closure $f
     * @return \ScalikePHP\Seq
     */
    final public static function create(Closure $f): Seq
    {
        return self::fromTraversable(new ClosureIterator($f));
    }

    /**
     * Get an empty Seq instance.
     *
     * @return \ScalikePHP\Seq
     */
    final public static function empty(): Seq
    {
        if (self::$empty === null) {
            self::$empty = new ArraySeq([]);
        }
        return self::$empty;
    }

    /**
     * Get an empty Seq instance.
     *
     * @return \ScalikePHP\Seq
     * @deprecated
     */
    final public static function emptySeq(): Seq
    {
        return self::empty();
    }

    /**
     * Create a Seq instance from arguments.
     *
     * @param array $items
     * @return \ScalikePHP\Seq
     */
    final public static function from(...$items): Seq
    {
        return new ArraySeq($items);
    }

    /**
     * Create an instance from an iterable.
     *
     * @param null|iterable $iterable
     * @throws InvalidArgumentException
     * @return \ScalikePHP\Seq
     */
    final public static function fromArray(?iterable $iterable): Seq
    {
        if ($iterable === null) {
            return self::empty();
        } elseif (is_array($iterable)) {
            return empty($iterable) ? static::empty() : new ArraySeq((array)$iterable);
        } elseif ($iterable instanceof Traversable) {
            return self::fromTraversable($iterable);
        } else {
            throw new InvalidArgumentException('Seq::fromArray() needs to iterable');
        }
    }

    /**
     * Create an instance from an iterator.
     *
     * @param Traversable $traversable
     * @return \ScalikePHP\Seq
     */
    final public static function fromTraversable(Traversable $traversable): Seq
    {
        return new TraversableSeq($traversable);
    }

    /**
     * Create an instance from two iterables.
     *
     * @param iterable $a
     * @param iterable $b
     * @return \ScalikePHP\Seq
     */
    final public static function merge(iterable $a, iterable $b): Seq
    {
        return self::create(function () use ($a, $b) {
            $i = 0;
            foreach ($a as $x) {
                yield $i++ => $x;
            }
            foreach ($b as $x) {
                yield $i++ => $x;
            }
        });
    }
}
