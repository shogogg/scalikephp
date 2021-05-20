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
use InvalidArgumentException;
use ScalikePHP\Support\SeqBuilder;

/**
 * Scala like Seq.
 */
abstract class Seq extends ScalikeTraversable
{
    use SeqBuilder;

    /**
     * Returns a new sequence containing the elements from this sequence followed by the elements from `$that`.
     *
     * @param iterable $that the iterable to append.
     * @return \ScalikePHP\Seq
     */
    abstract public function append(iterable $that): self;

    /**
     * Returns the evaluated sequence.
     *
     * @return \ScalikePHP\Seq
     */
    abstract public function computed(): self;

    /**
     * Tests whether this sequence contains a given value as an element.
     *
     * @param mixed $elem the element to test.
     * @return bool true if this sequence has an element that is equal (as determined by `===`) to `$elem`, false otherwise.
     */
    abstract public function contains($elem): bool;

    /**
     * Selects all the elements of this sequence ignoring the duplicates.
     *
     * @return \ScalikePHP\Seq a new sequence consisting of all the elements of this sequence without duplicates.
     */
    abstract public function distinct(): self;

    /**
     * Selects all the elements of this sequence ignoring the duplicates
     * as determined by `===` after applying the transforming function `$f`.
     *
     * @param Closure $f the transforming function whose result is used to determine the uniqueness of each element.
     * @return \ScalikePHP\Seq a new sequence consisting of all the elements of this sequence without duplicates.
     */
    abstract public function distinctBy(Closure $f): self;

    /**
     * Finds index of first occurrence of some value in this sequence.
     *
     * @param mixed $elem the element value to search for.
     * @return int the index >= 0 of the first element of this sequence that is equal (as determined by `===`) to elem,
     *             or -1, if none exists.
     */
    abstract public function indexOf($elem): int;

    /**
     * Returns a new sequence containing the elements from `$that` followed by the elements from this sequence.
     *
     * @param iterable $that the iterable to prepend.
     * @return \ScalikePHP\Seq
     */
    abstract public function prepend(iterable $that): self;

    /**
     * Returns new sequence with elements in reversed order.
     *
     * @return \ScalikePHP\Seq
     */
    abstract public function reverse(): self;

    /**
     * Sorts this sequence according to the ordering with a transformation function.
     *
     * @param Closure|string $f the transformation function or key of each element.
     * @return \ScalikePHP\Seq a sequence consisting of the elements of this sequence sorted.
     */
    abstract public function sortBy($f): self;

    /**
     * Converts this sequence to a Map.
     *
     * @param Closure|string $key
     * @throws InvalidArgumentException
     * @return \ScalikePHP\Map
     */
    abstract public function toMap($key): Map;
}
