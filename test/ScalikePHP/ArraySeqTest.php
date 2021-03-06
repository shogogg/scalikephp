<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\Implementations\ArraySeq;
use ScalikePHP\Seq;

/**
 * Tests for {@link \ScalikePHP\Implementations\ArraySeq}.
 *
 * @internal
 */
final class ArraySeqTest extends TestCase
{
    use SeqTestCases;

    /**
     * {@inheritdoc}
     */
    protected function seq(...$values): Seq
    {
        return new ArraySeq($values);
    }

    /**
     * @test
     * @covers \ScalikePHP\Implementations\ArraySeq::computed()
     */
    public function testComputed(): void
    {
        $seq = $this->seq('foo', 'bar', 'baz');
        Assert::same($seq, $seq->computed());
    }
}
