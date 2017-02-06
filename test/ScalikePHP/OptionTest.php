<?php
namespace Test\ScalikePHP;

use ScalikePHP\None;
use ScalikePHP\Option;
use ScalikePHP\Some;

/**
 * Tests for Option.
 *
 * @see \ScalikePHP\Option
 */
class OptionTest extends TestCase
{

    /**
     * Tests for Option::from().
     *
     * @see \ScalikePHP\Option::from()
     */
    public function testFrom()
    {
        self::assertInstanceOf(Some::class, Option::from(0));
        self::assertInstanceOf(Some::class, Option::from("abc"));
        self::assertInstanceOf(None::class, Option::from(null));
        self::assertInstanceOf(None::class, Option::from(0, 0));
        self::assertInstanceOf(None::class, Option::from("abc", "abc"));
    }

    /**
     * Tests for Option::fromArray().
     *
     * @see \ScalikePHP\Option::fromArray()
     */
    public function testFromArray()
    {
        $array = ["foo" => "bar"];
        self::assertInstanceOf(Some::class, Option::fromArray($array, "foo"));
        self::assertInstanceOf(None::class, Option::fromArray($array, "bar"));
        self::assertSame("bar", Option::fromArray($array, "foo")->get());
    }

    /**
     * Tests for Option::none().
     *
     * @see \ScalikePHP\Option::none()
     */
    public function testNone()
    {
        self::assertInstanceOf(None::class, Option::none());
    }

    /**
     * Tests for Option::some().
     *
     * @see \ScalikePHP\Option::some()
     */
    public function testSome()
    {
        self::assertInstanceOf(Some::class, Option::some(1));
        self::assertInstanceOf(Some::class, Option::some("abc"));
        self::assertInstanceOf(Some::class, Option::some(null));
        self::assertSame(1, Option::some(1)->get());
        self::assertSame("abc", Option::some("abc")->get());
        self::assertSame(null, Option::some(null)->get());
    }

}
