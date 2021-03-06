<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use ScalikePHP\None;
use ScalikePHP\Option;
use ScalikePHP\Some;

/**
 * Tests for {@link \ScalikePHP\Option}.
 *
 * @internal
 */
final class OptionTest extends TestCase
{
    /**
     * @test
     * @covers \ScalikePHP\Option::from()
     */
    public function testFrom(): void
    {
        Assert::instanceOf(Some::class, Option::from(0));
        Assert::instanceOf(Some::class, Option::from('abc'));
        Assert::instanceOf(None::class, Option::from(null));
        Assert::instanceOf(None::class, Option::from(0, 0));
        Assert::instanceOf(None::class, Option::from('abc', 'abc'));
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::fromArray()
     */
    public function testFromArray(): void
    {
        $array = ['foo' => 'bar'];
        Assert::instanceOf(Some::class, Option::fromArray($array, 'foo'));
        Assert::instanceOf(None::class, Option::fromArray($array, 'bar'));
        Assert::same('bar', Option::fromArray($array, 'foo')->get());
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::none()
     */
    public function testNone(): void
    {
        Assert::instanceOf(None::class, Option::none());
    }

    /**
     * @test
     * @covers \ScalikePHP\Option::some()
     */
    public function testSome(): void
    {
        Assert::instanceOf(Some::class, Option::some(1));
        Assert::instanceOf(Some::class, Option::some('abc'));
        Assert::instanceOf(Some::class, Option::some(null));
        Assert::same(1, Option::some(1)->get());
        Assert::same('abc', Option::some('abc')->get());
        Assert::same(null, Option::some(null)->get());
    }
}
