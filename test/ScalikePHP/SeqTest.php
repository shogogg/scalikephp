<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ArrayIterator;
use Generator;
use ScalikePHP\ArraySeq;
use ScalikePHP\Seq;
use ScalikePHP\TraversableSeq;

/**
 * Tests for Seq.
 *
 * @see \ScalikePHP\Seq
 *
 * @internal
 * @coversNothing
 */
final class SeqTest extends TestCase
{
    /**
     * Tests for Seq::empty().
     *
     * @see \ScalikePHP\Seq::empty()
     */
    public function testEmpty(): void
    {
        Assert::true(Seq::empty()->isEmpty());
        Assert::same(Seq::empty(), Seq::empty());
    }

    /**
     * Tests for Seq::emptySeq().
     *
     * @see \ScalikePHP\Seq::emptySeq()
     */
    public function testEmptySeq(): void
    {
        Assert::true(Seq::emptySeq()->isEmpty());
        Assert::same(Seq::emptySeq(), Seq::empty());
    }

    /**
     * Tests for Seq::from().
     *
     * @see \ScalikePHP\Seq::from()
     */
    public function testFrom(): void
    {
        Assert::instanceOf(ArraySeq::class, Seq::from(1, 2, 3));
        Assert::same([1, 2, 3], Seq::from(1, 2, 3)->toArray());
    }

    /**
     * Tests for Seq::fromArray().
     *
     * @see \ScalikePHP\Seq::fromArray()
     */
    public function testFromArray(): void
    {
        $array = [1, 2, 3];
        $generator = (function (): Generator {
            for ($i = 1; $i <= 3; ++$i) {
                yield $i;
            }
        })();
        $iterator = new ArrayIterator($array);
        Assert::instanceOf(ArraySeq::class, Seq::fromArray($array));
        Assert::instanceOf(TraversableSeq::class, Seq::fromArray($generator));
        Assert::instanceOf(TraversableSeq::class, Seq::fromArray($iterator));
        Assert::same([1, 2, 3], Seq::fromArray($array)->toArray());
        Assert::same([1, 2, 3], Seq::fromArray($generator)->toArray());
        Assert::same([1, 2, 3], Seq::fromArray($iterator)->toArray());
    }
}
