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
use ScalikePHP\ScalikeTraversable;
use ScalikePHP\Seq;
use ScalikePHP\TraversableSeq;

/**
 * Support functions for Seq.
 */
trait SeqSupport
{

    use GeneralSupport;

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::drop()
     */
    public function drop(int $n): Seq
    {
        return new TraversableSeq($this->dropGenerator($this->getRawIterable(), $n));
    }

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::filter()
     */
    public function filter(\Closure $p): Seq
    {
        return new TraversableSeq($this->filterGenerator($this->getRawIterable(), $p));
    }

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::flatMap()
     */
    public function flatMap(\Closure $f): Seq
    {
        return new TraversableSeq($this->flatMapGenerator($this->getRawIterable(), $f));
    }

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::flatten()
     */
    public function flatten(): Seq
    {
        return new TraversableSeq($this->flattenGenerator($this->getRawIterable()));
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->getRawIterable() as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::map()
     */
    public function map(\Closure $f): Seq
    {
        return new TraversableSeq($this->mapGenerator($this->getRawIterable(), $f));
    }

    /**
     * @inheritdoc
     * @see Seq::sumBy()
     */
    public function sumBy(\Closure $f)
    {
        return $this->fold(0, $f);
    }

    /**
     * @inheritdoc
     * @return Seq
     * @see Seq::take()
     */
    public function take(int $n): Seq
    {
        return $n <= 0 ? Seq::emptySeq() : new TraversableSeq($this->takeGenerator($this->getRawIterable(), $n));
    }

    /**
     * Crate a dropped generator.
     *
     * @param iterable $iterable
     * @param int $n
     * @return \Generator
     */
    private function dropGenerator(iterable $iterable, int $n): \Generator
    {
        $i = $n;
        foreach ($iterable as $value) {
            if ($i <= 0) {
                yield $value;
            } else {
                --$i;
            }
        }
    }

    /**
     * Create a Generator from iterable with filter.
     *
     * @param iterable $iterable
     * @param \Closure $p
     * @return \Generator
     */
    private function filterGenerator(iterable $iterable, \Closure $p): \Generator
    {
        foreach ($iterable as $value) {
            if ($p($value)) {
                yield $value;
            }
        }
    }

    /**
     * Create a Generator from iterable with flatmap.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Generator
     * @throws \LogicException
     */
    private function flatMapGenerator(iterable $iterable, \Closure $f): \Generator
    {
        foreach ($iterable as $value) {
            $xs = $f($value);
            if (is_iterable($xs) === false) {
                throw new \LogicException("Closure should returns an iterable");
            }
            foreach ($xs as $x) {
                yield $x;
            }
        }
    }

    /**
     * Create a Generator from iterable with flatten.
     *
     * @param iterable $iterable
     * @return \Generator
     * @throws \LogicException
     */
    private function flattenGenerator(iterable $iterable): \Generator
    {
        foreach ($iterable as $value) {
            if (is_iterable($value) === false) {
                throw new \LogicException("Closure should returns an iterable");
            }
            foreach ($value as $x) {
                yield $x;
            }
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Generator
     */
    private function mapGenerator(iterable $iterable, \Closure $f): \Generator
    {
        foreach ($iterable as $value) {
            yield $f($value);
        }
    }

    /**
     * Create a Generator from two iterables.
     *
     * @param iterable $a
     * @param iterable $b
     * @return \Generator
     */
    protected function mergeGenerator(iterable $a, iterable $b): \Generator
    {
        foreach ($a as $value) {
            yield $value;
        }
        foreach ($b as $value) {
            yield $value;
        }
    }

    /**
     * Create a Generator from first $n elements of iterable.
     *
     * @param iterable $iterable
     * @param int $n
     * @return \Generator
     */
    private function takeGenerator(iterable $iterable, int $n): \Generator
    {
        $i = $n;
        foreach ($iterable as $value) {
            yield $value;
            if (--$i <= 0) {
                break;
            }
        }
    }

}
