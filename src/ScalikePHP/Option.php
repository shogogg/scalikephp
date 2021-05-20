<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use Closure;
use Generator;
use RuntimeException;
use ScalikePHP\Support\OptionBuilder;

/**
 * Scala like Option.
 */
abstract class Option extends ScalikeTraversable
{
    use OptionBuilder;

    /**
     * 値を返す, 値を持たない場合は例外を投げる.
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    abstract public function get();

    /**
     * 値を返す, 値を持たない場合は $default の戻り値を返す.
     *
     * @param Closure $default デフォルト値を返す関数
     *
     * @return mixed
     */
    abstract public function getOrElse(Closure $default);

    /**
     * 値を返す, 値を持たない場合は $default の値を返す.
     *
     * @param mixed $default デフォルト値
     *
     * @return mixed
     */
    abstract public function getOrElseValue($default);

    /**
     * 値を持っているかどうかを判定する.
     *
     * @return bool
     */
    abstract public function isDefined(): bool;

    /**
     * Some の場合は自身を返し, None の場合は引数で渡されたクロージャの戻り値を返す.
     *
     * @param Closure $b
     *
     * @return \ScalikePHP\Option
     */
    abstract public function orElse(Closure $b): self;

    /**
     * 値を返す, 値を持たない場合は null を返す.
     *
     * @return null|mixed
     */
    abstract public function orNull();

    /**
     * 値が配列またはオブジェクトの場合に、与えられたキーの値を取得する.
     *
     * 値を持たないか、与えられたキーに対応する要素・プロパティが存在しない場合は None を返す
     *
     * @param string $name
     * @return \ScalikePHP\Option
     */
    abstract public function pick(string $name): self;

    /**
     * {@inheritdoc}
     */
    public function take(int $n): self
    {
        return $n <= 0 ? self::none() : $this;
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight(int $n): self
    {
        return $n <= 0 ? self::none() : $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toGenerator(): Generator
    {
        foreach ($this->toArray() as $index => $value) {
            yield $index => $value;
        }
    }
}
