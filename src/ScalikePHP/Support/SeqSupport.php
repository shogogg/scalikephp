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
 *
 * @mixin \ScalikePHP\ScalikeTraversable
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
        return new TraversableSeq(function () use ($n): \Generator {
            $i = $n;
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if ($i <= 0) {
                    yield $index++ => $value;
                } else {
                    --$i;
                }
            }
        });
    }

    /** {@inheritdoc} */
    public function filter(\Closure $p): Seq
    {
        return new TraversableSeq(function () use ($p): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if ($p($value)) {
                    yield $index++ => $value;
                }
            }
        });
    }

    /** {@inheritdoc} */
    public function flatMap(\Closure $f): Seq
    {
        return new TraversableSeq(function () use ($f): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                $xs = $f($value);
                if (is_iterable($xs) === false) {
                    throw new \LogicException("Closure should returns an iterable");
                }
                foreach ($xs as $x) {
                    yield $index++ => $x;
                }
            }
        });
    }

    /** {@inheritdoc} */
    public function flatten(): Seq
    {
        return new TraversableSeq(function (): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if (is_iterable($value) === false) {
                    throw new \LogicException("Closure should returns an iterable");
                }
                foreach ($value as $x) {
                    yield $index++ => $x;
                }
            }
        });
    }

    /** {@inheritdoc} */
    public function fold($z, \Closure $f)
    {
        foreach ($this->getRawIterable() as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /** {@inheritdoc} */
    public function map(\Closure $f): Seq
    {
        return new TraversableSeq(function () use ($f): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                yield $index++ => $f($value);
            }
        });
    }

    /** {@inheritdoc} */
    public function sumBy(\Closure $f)
    {
        return $this->fold(0, $f);
    }

    /** {@inheritdoc} */
    public function take(int $n): Seq
    {
        return $n <= 0 ? Seq::emptySeq() : new TraversableSeq(function () use ($n): \Generator {
            $i = $n;
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                yield $index++ => $value;
                if (--$i <= 0) {
                    break;
                }
            }
        });
    }

}
