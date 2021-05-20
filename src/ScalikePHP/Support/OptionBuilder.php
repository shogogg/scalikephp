<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

use ArrayAccess;
use ScalikePHP\None;
use ScalikePHP\Option;
use ScalikePHP\Some;

/**
 * Option building functions.
 */
trait OptionBuilder
{
    /**
     * Returns an Option of the value.
     *
     * @param mixed $value
     * @param mixed $none
     * @return \ScalikePHP\Option
     */
    final public static function from($value, $none = null): Option
    {
        return $value === $none ? self::none() : self::some($value);
    }

    /**
     * Returns an Option of the array element.
     *
     * @param array|ArrayAccess $array
     * @param int|string $key
     * @param mixed $none
     * @return \ScalikePHP\Option
     */
    final public static function fromArray($array, $key, $none = null): Option
    {
        return isset($array[$key]) ? self::from($array[$key], $none) : self::none();
    }

    /**
     * Returns a Some of the value.
     *
     * @param mixed $value
     * @return \ScalikePHP\Some
     */
    final public static function some($value): Some
    {
        return Some::create($value);
    }

    /**
     * Returns the None instance.
     *
     * @return \ScalikePHP\None
     */
    final public static function none(): None
    {
        return None::getInstance();
    }
}
