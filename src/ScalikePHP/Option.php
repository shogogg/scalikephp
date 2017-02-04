<?php
namespace ScalikePHP;

/**
 * Scala like Option
 */
abstract class Option extends ScalikeTraversable
{

    /**
     * 与えられた値から Option を生成する
     *
     * @param mixed $value 値
     * @param mixed $none $value を None とする判定に使用する値（デフォルト: null）
     * @return Option 与えられた値が $none に等しい場合に None, そうでない場合は Some
     */
    final public static function from($value, $none = null): Option
    {
        return $value === $none ? static::none() : static::some($value);
    }

    /**
     * 配列から Option を生成する
     *
     * @param array|\ArrayAccess $array 配列
     * @param string $key 対象のキー
     * @param mixed $none $array から見つかった要素を None とする判定に使用する値（デフォルト: null）
     * @return Option $array に $key が含まれないか、その値が $none に等しい場合に None, そうでない場合は Some
     */
    final public static function fromArray($array, $key, $none = null): Option
    {
        return isset($array[$key]) ? static::from($array[$key], $none) : static::none();
    }

    /**
     * Get a Some instance
     *
     * @param mixed $value
     * @return Option
     */
    final public static function some($value): Option
    {
        return Some::create($value);
    }

    /**
     * Get a None instance
     *
     * @return Option
     */
    final public static function none(): Option
    {
        return None::getInstance();
    }

    /**
     * 値を返す, 値を持たない場合は例外を投げる
     *
     * @return mixed
     * @throws \RuntimeException
     */
    abstract public function get();

    /**
     * 値を返す, 値を持たない場合は関数を実行し、その戻り値を返す
     *
     * @param \Closure $f デフォルト値を返す関数
     * @return mixed
     */
    abstract public function getOrCall(\Closure $f);

    /**
     * 値を返す, 値を持たない場合は $default の値を返す
     *
     * @param mixed $default デフォルト値
     * @return mixed
     */
    abstract public function getOrElse($default);

    /**
     * 値を返す, 値を持たない場合は null を返す
     *
     * @return mixed
     * @deprecated Use `orNull`.
     */
    abstract public function getOrNull();

    /**
     * 値を返す, 値を持たない場合は $exception を投げる
     *
     * @param \Exception $exception
     * @return mixed
     * @throws \Exception
     */
    abstract public function getOrThrow(\Exception $exception);

    /**
     * 値を持っているかどうかを判定する
     *
     * @return bool
     */
    abstract public function isDefined(): bool;

    /**
     * Some の場合は自身を返し, None の場合は引数の値を返す
     *
     * @param Option $b
     * @return Option
     */
    abstract public function orElse(Option $b);

    /**
     * 値を返す, 値を持たない場合は null を返す
     *
     * @return mixed
     */
    abstract public function orNull();

    /**
     * Some の場合は自身を返し, None の場合は引数の関数を実行してその戻り値を返す
     *
     * @param \Closure $f
     * @return Option
     */
    abstract public function orElseCall(\Closure $f): Option;

    /**
     * 値が配列またはオブジェクトの場合に、与えられたキーの値を取得する
     *
     * 値を持たないか、与えられたキーに対応する要素・プロパティが存在しない場合は None を返す
     *
     * @param string $name
     * @return Option
     */
    abstract public function pick($name): Option;

}
