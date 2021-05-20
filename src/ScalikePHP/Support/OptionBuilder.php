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
     * 与えられた値から Option を生成する.
     *
     * @param mixed $value 値
     * @param mixed $none $value を None とする判定に使用する値（デフォルト: null）
     * @return \ScalikePHP\Option 与えられた値が $none に等しい場合に None, そうでない場合は Some
     */
    final public static function from($value, $none = null): Option
    {
        return $value === $none ? self::none() : self::some($value);
    }

    /**
     * 配列から Option を生成する.
     *
     * @param array|ArrayAccess $array 配列
     * @param string $key 対象のキー
     * @param mixed $none $array から見つかった要素を None とする判定に使用する値（デフォルト: null）
     * @return \ScalikePHP\Option $array に $key が含まれないか、その値が $none に等しい場合に None, そうでない場合は Some
     */
    final public static function fromArray($array, $key, $none = null): Option
    {
        return isset($array[$key]) ? self::from($array[$key], $none) : self::none();
    }

    /**
     * Get a Some instance.
     *
     * @param mixed $value
     *
     * @return \ScalikePHP\Some
     */
    final public static function some($value): Some
    {
        return Some::create($value);
    }

    /**
     * Get a None instance.
     *
     * @return \ScalikePHP\None
     */
    final public static function none(): None
    {
        return None::getInstance();
    }
}
