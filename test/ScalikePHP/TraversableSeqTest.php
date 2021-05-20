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
use ScalikePHP\Implementations\ArraySeq;
use ScalikePHP\Implementations\TraversableSeq;
use ScalikePHP\Seq;

/**
 * Tests for {@link \ScalikePHP\Implementations\TraversableSeq}.
 *
 * @internal
 */
final class TraversableSeqTest extends TestCase
{
    use SeqTestCases;

    /**
     * {@inheritdoc}
     */
    protected function seq(...$values): Seq
    {
        return new TraversableSeq(new ArrayIterator($values));
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\TraversableSeq::computed()
     */
    public function testComputed(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        $actual = $seq->computed();
        Assert::instanceOf(ArraySeq::class, $actual);
        Assert::same($seq->toArray(), $actual->toArray());
    }
}
