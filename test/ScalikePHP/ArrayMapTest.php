<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\Implementations\ArrayMap;
use ScalikePHP\Map;

/**
 * Tests for {@link \ScalikePHP\Implementations\ArrayMap}.
 *
 * @internal
 */
final class ArrayMapTest extends TestCase
{
    use MapTestCases;

    /**
     * {@inheritdoc}
     */
    protected function map(array $values = []): Map
    {
        return new ArrayMap($values);
    }
}
