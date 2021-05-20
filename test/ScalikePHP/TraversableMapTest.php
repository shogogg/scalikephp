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
use ScalikePHP\Map;
use ScalikePHP\TraversableMap;

/**
 * Tests for {@link \ScalikePHP\TraversableMap}.
 *
 * @internal
 */
final class TraversableMapTest extends TestCase
{
    use MapTestCases;

    /**
     * {@inheritdoc}
     */
    protected function map(array $values = []): Map
    {
        return new TraversableMap(new ArrayIterator($values));
    }
}
