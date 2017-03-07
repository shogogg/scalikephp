<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace Test\ScalikePHP;

use ScalikePHP\Map;
use ScalikePHP\TraversableMap;

/**
 * Tests for TraversableMap.
 *
 * @see \ScalikePHP\TraversableMap
 */
class TraversableMapTest extends TestCase
{

    use MapTestCases;

    /**
     * @inheritdoc
     */
    protected function map(array $values = []): Map
    {
        $g = function () use ($values) {
            foreach ($values as $key => $value) {
                yield $key => $value;
            }
        };
        return new TraversableMap($g());
    }

}
