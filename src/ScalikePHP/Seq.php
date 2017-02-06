<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * Scala like Seq.
 */
abstract class Seq extends ScalikeTraversable
{

    /**
     * 空の Seq
     *
     * @var Seq
     */
    private static $empty = null;

    /**
     * Get an empty Seq instance
     *
     * @return Seq
     */
    public static function emptySeq(): Seq
    {
        if (static::$empty === null) {
            static::$empty = new IterableSeq([]);
        }
        return static::$empty;
    }

    /**
     * Create a Seq instance from arguments
     *
     * @param mixed[] $items
     * @return Seq
     */
    public static function from(... $items): Seq
    {
        return new IterableSeq($items);
    }

    /**
     * Create a Seq instance from an array (or \Traversable)
     *
     * @param iterable|null $iterable
     * @return Seq
     * @throws \InvalidArgumentException
     */
    public static function fromArray(?iterable $iterable): Seq
    {
        if ($iterable === null) {
            return static::emptySeq();
        } elseif (is_iterable($iterable)) {
            return new IterableSeq($iterable);
        } else {
            throw new \InvalidArgumentException("Seq::fromArray() needs to iterable");
        }
    }

    /**
     * 末尾に要素を追加する
     *
     * @param iterable $that
     * @return Seq
     */
    abstract public function append(iterable $that): Seq;

    /**
     * 指定された値が含まれているかどうかを判定する
     *
     * @param mixed $elem
     * @return bool
     */
    abstract public function contains($elem): bool;

    /**
     * 重複を排除した Seq を返す
     * 
     * @return Seq
     */
    abstract public function distinct(): Seq;

    /**
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param \Closure $f
     * @return mixed
     */
    abstract public function fold($z, \Closure $f);

    /**
     * 先頭に要素を追加する
     *
     * @param iterable $that
     * @return Seq
     */
    abstract public function prepend(iterable $that): Seq;

    /**
     * 逆順にした Seq を返す
     *
     * @return Seq
     */
    abstract public function reverse(): Seq;

    /**
     * 指定された関数の戻り値（または指定されたキーの値）を用いてソートされた Seq を返す
     *
     * @param string|\Closure $f
     * @return Seq
     */
    abstract public function sortBy($f): Seq;

    /**
     * Map に変換する
     *
     * $key に string が渡された場合は各要素から $key に該当する要素|プロパティを探し、それをキーとする
     * $key に \Closure が渡された場合は各要素を引数として $key を実行し、それをキーとする
     *
     * @param string|\Closure $key
     * @return Map
     */
    abstract public function toMap($key): Map;

}
