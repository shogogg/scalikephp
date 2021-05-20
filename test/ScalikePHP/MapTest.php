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
 * Tests for {@link \ScalikePHP\Map}.
 *
 * @internal
 */
final class MapTest extends TestCase
{
    /**
     * @test
     * @covers \ScalikePHP\Map::empty()
     */
    public function testEmpty(): void
    {
        Assert::true(Map::empty()->isEmpty());
        Assert::same(Map::empty(), Map::empty());
    }

    /**
     * @test
     * @covers \ScalikePHP\Map::emptyMap()
     */
    public function testEmptyMap(): void
    {
        Assert::true(Map::emptyMap()->isEmpty());
        Assert::same(Map::emptyMap(), Map::empty());
    }
}
