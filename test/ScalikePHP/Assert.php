<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace Test\ScalikePHP;

use Exception;
use ScalikePHP\None;
use ScalikePHP\Option;
use ScalikePHP\Some;

final class Assert
{
    /**
     * Asserts that a condition is false.
     *
     * @param bool $condition
     * @param string $message
     * @see TestCase::assertFalse()
     */
    public static function false(bool $condition, string $message = ''): void
    {
        TestCase::assertFalse($condition, $message);
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed $actual
     * @param string $message
     * @see TestCase::assertInstanceOf()
     */
    public static function instanceOf(string $expected, $actual, string $message = ''): void
    {
        TestCase::assertInstanceOf($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is instance of None.
     *
     * @param Option $actual
     * @param string $message
     */
    public static function none(Option $actual, string $message = ''): void
    {
        TestCase::assertInstanceOf(None::class, $actual, $message);
    }

    /**
     * Asserts that two variables have the same type and value.
     *
     * @param $expected
     * @param $actual
     * @param string $message
     * @see TestCase::assertSame()
     */
    public static function same($expected, $actual, string $message = ''): void
    {
        TestCase::assertSame($expected, $actual, $message);
    }

    /**
     * Asserts that a variable is instance of Some, and it's value same as `$expected`.
     *
     * @param mixed $expected
     * @param Option $actual
     * @param string $message
     */
    public static function some($expected, Option $actual, string $message = ''): void
    {
        TestCase::assertInstanceOf(Some::class, $actual, $message);
        TestCase::assertSame($expected, $actual->get(), $message);
    }

    /**
     * Asserts that an exception throws in block.
     *
     * @param string $expected
     * @param \Closure $block
     * @param string $message
     */
    public static function throws(string $expected, \Closure $block, string $message = ''): void
    {
        try {
            $block();
            TestCase::fail($message);
        } catch (\Exception $exception) {
            TestCase::assertInstanceOf($expected, $exception, $message);
        }
    }

    /**
     * Asserts that a condition is true.
     *
     * @param bool $condition
     * @param string $message
     * @see TestCase::assertTrue()
     */
    public static function true(bool $condition, string $message = ''): void
    {
        TestCase::assertTrue($condition, $message);
    }
}
