<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\Map;

/**
 * Tests for Map.
 *
 * @see \ScalikePHP\Map
 *
 * @internal
 * @coversNothing
 */
final class MapTest extends TestCase
{
    /**
     * Tests for Map::empty().
     *
     * @see \ScalikePHP\Map::empty()
     */
    public function testEmpty(): void
    {
        Assert::true(Map::empty()->isEmpty());
        Assert::same(Map::empty(), Map::empty());
    }

    /**
     * Tests for Map::emptyMap().
     *
     * @see \ScalikePHP\Map::emptyMap()
     */
    public function testEmptyMap(): void
    {
        Assert::true(Map::emptyMap()->isEmpty());
        Assert::same(Map::emptyMap(), Map::empty());
    }
}
