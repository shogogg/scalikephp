<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace Test\ScalikePHP;

use ScalikePHP\Seq;
use ScalikePHP\TraversableSeq;

/**
 * Tests for TraversableSeq.
 *
 * @see \ScalikePHP\TraversableSeq
 */
class TraversableSeqTest extends TestCase
{

    use SeqTestCases;

    /**
     * @inheritdoc
     */
    protected function seq(... $values): Seq
    {
        return new TraversableSeq(function () use ($values) {
            foreach ($values as $value) {
                yield $value;
            }
        });
    }

}
