<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use Generator;

/**
 * Scala like Traversable Interface.
 */
interface ScalikeTraversableInterface extends \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    /**
     * Selects all elements except first `$n` ones.
     *
     * @param int $n the number of elements to drop from this collection.
     * @return static a collection consisting of all elements of this collection except the first n ones,
     *                or else the empty collection, if this collection has less than n elements.
     *                If n is negative, don't drop any elements.
     */
    public function drop(int $n): self;

    /**
     * Apply `$f` to each element for its side effects.
     *
     * @param \Closure $f the function to apply to each element.
     */
    public function each(\Closure $f): void;

    /**
     * Tests whether a predicate holds for at least one element of this collection.
     *
     * @param \Closure $p the predicate used to test elements.
     * @return bool true if the given predicate `$p` is satisfied by at least one element of this collection,
     *              otherwise false.
     */
    public function exists(\Closure $p): bool;

    /**
     * Selects all elements of this collection which satisfy a predicate.
     *
     * @param \Closure $p the predicate used to test elements.
     * @return static a new collection consisting of all elements of this collection that do not satisfy the given predicate `$p`.
     */
    public function filter(\Closure $p): self;

    /**
     * Selects all elements of this collection which do not satisfy a predicate.
     *
     * @param \Closure $p the predicate used to test elements.
     * @return static a new collection consisting of all elements of this collection that satisfy the given predicate `$p`.
     */
    public function filterNot(\Closure $p): self;

    /**
     * Finds the first element of this collection satisfying a predicate, if any.
     *
     * @param \Closure $p the predicate used to test elements.
     * @return \ScalikePHP\Option an Option containing the first element in this collection that satisfies `$p`,
     *                            or None if none exists.
     */
    public function find(\Closure $p): Option;

    /**
     * Converts this collection of traversable collections into a collection formed by the elements of these collections.
     *
     * @return static a new collection resulting from concatenating all element collections.
     */
    public function flatten(): self;

    /**
     * Builds a new collection by applying a function to all elements of this collection and using the elements of the resulting collections.
     *
     * @param \Closure $f the function to apply to each element.
     * @return static a new collection resulting from concatenating all element collections.
     */
    public function flatMap(\Closure $f): self;

    /**
     * Folds the elements of this collection using the specified associative binary operator.
     *
     * @param mixed $z a neutral element for the fold operation; may be added to the result an arbitrary number of times,
     *                 and must not change the result (e.g., Nil for list concatenation, 0 for addition, or 1 for multiplication).
     * @param \Closure $op a binary operator that must be associative.
     * @return mixed the result of applying the fold operator op between all the elements and z, or z if this collection is empty.
     */
    public function fold($z, \Closure $op);

    /**
     * Tests whether a predicate holds for all elements of this collection.
     *
     * @param \Closure $p the predicate used to test elements.
     * @return bool true if this collection is empty or the given predicate p holds for all elements of this collection,
     *              otherwise false.
     */
    public function forAll(\Closure $p): bool;

    /**
     * Creates a new {@link Generator} from this collection.
     *
     * @param \Closure $f the function to apply to each element. it should returns a {@link Generator}.
     * @return \Generator
     */
    public function generate(\Closure $f): \Generator;

    /**
     * Partitions this collection into a map of collections according to some discriminator function.
     *
     * @param \Closure|string $f the discriminator function or key of element.
     * @return \ScalikePHP\Map|\ScalikePHP\Seq[] A map from keys to collections.
     */
    public function groupBy($f): Map;

    /**
     * Selects the first element of this collection.
     *
     * @throws \LogicException if this collection is empty.
     * @return mixed the first element of this collection.
     */
    public function head();

    /**
     * Optionally selects the first element.
     *
     * @return \ScalikePHP\Option the first element of this collection if it is non-empty, None if it is empty.
     */
    public function headOption(): Option;

    /**
     * Tests whether this collection is empty.
     *
     * @return bool true if this collection contains no elements, false otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Selects the last element of this collection.
     *
     * @throws \LogicException if this collection is empty.
     * @return mixed the last element of this collection.
     */
    public function last();

    /**
     * Optionally selects the last element.
     *
     * @return \ScalikePHP\Option the last element of this collection if it is non-empty, None if it is empty.
     */
    public function lastOption(): Option;

    /**
     * Builds a new collection by applying a function to all elements of this collection.
     *
     * @param \Closure $f the function to apply to each element.
     * @return static
     */
    public function map(\Closure $f): self;

    /**
     * Finds the largest element.
     *
     * @throws \LogicException if this collection is empty.
     * @return mixed the largest element of this collection.
     */
    public function max();

    /**
     * Finds the first element which yields the largest value measured by function `$f`.
     *
     * @param \Closure $f the measuring function.
     * @return mixed the first element of this collection with the largest value measured by function `$f`.
     */
    public function maxBy(\Closure $f);

    /**
     * Finds the smallest element.
     *
     * @throws \LogicException if this collection is empty.
     * @return mixed the smallest element of this collection.
     */
    public function min();

    /**
     * Finds the first element which yields the smallest value measured by function `$f`.
     *
     * @param \Closure $f the measuring function.
     * @return mixed the first element of this collection with the smallest value measured by function `$f`.
     */
    public function minBy(\Closure $f);

    /**
     * Returns all elements of this collection in a string using a separator string.
     *
     * @param string $sep the separator string.
     * @return string a string representation of this collection.
     *                In the resulting string the string representations (w.r.t. the method __toString) of all elements
     *                of this collection are separated by the string sep.
     */
    public function mkString(string $sep = ''): string;

    /**
     * Tests whether this collection is not empty.
     *
     * @return bool true if this collection does not contain any elements, false otherwise.
     */
    public function nonEmpty(): bool;

    /**
     * Splits this collection in two: all elements that satisfy predicate `$p` and all elements that do not.
     *
     * @return array|self[] an array of, first, all elements that satisfy predicate p and,
     *                      second, all elements that do not.
     */
    public function partition(\Closure $p): array;

    /**
     * Returns the size of this collection.
     *
     * @return int
     */
    public function size(): int;

    /**
     * Sums up the elements of this collection.
     *
     * @see https://www.php.net/manual/en/function.array-sum.php
     * @return float|int the sum of all elements of this collection.
     */
    public function sum();

    /**
     * Sums up the elements of this collection.
     *
     * @param \Closure $f the measuring function.
     * @return float|int the sum of all elements of this collection.
     */
    public function sumBy(\Closure $f);

    /**
     * Returns rest of this collection without its first element.
     *
     * @return static
     */
    public function tail(): self;

    /**
     * Selects the first `$n` elements.
     *
     * @param int $n the number of elements to take from this collection.
     * @return static a collection consisting only of the first `$n` elements of this collection,
     *                or else the whole collection, if it has less than `$n` elements.
     *                If `$n` is negative, returns an empty collection.
     */
    public function take(int $n): self;

    /**
     * Selects the last `$n` elements.
     *
     * @param int $n the number of elements to take from this collection.
     * @return static a collection consisting only of the last `$n` elements of this collection,
     *                or else the whole collection, if it has less than `$n` elements.
     *                If `$n` is negative, returns an empty collection.
     */
    public function takeRight(int $n): self;

    /**
     * Converts this collection to an array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Converts this collection to a Generator.
     *
     * @return \Generator
     */
    public function toGenerator(): \Generator;

    /**
     * Converts this collection to a Seq.
     *
     * @return \ScalikePHP\Seq
     */
    public function toSeq(): Seq;
}
