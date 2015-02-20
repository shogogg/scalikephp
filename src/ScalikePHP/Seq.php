<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * Scala like Seq
 */
abstract class Seq extends ScalikeTraversable
{

    /**
     * Get an empty Seq instance
     *
     * @return Seq
     */
    public static function emptySeq()
    {
        $empty = null;
        if ($empty === null) {
            $empty = new ArraySeq([]);
        }
        return $empty;
    }

    /**
     * Create a Seq instance from arguments
     *
     * @return Seq
     */
    public static function from()
    {
        return new ArraySeq(func_get_args());
    }

    /**
     * Create a Seq instance from an array (or \Traversable)
     *
     * @param array|\Traversable $array
     * @return Seq
     * @throws \InvalidArgumentException
     */
    public static function fromArray($array)
    {
        if ($array === null) {
            return static::emptySeq();
        } elseif (is_array($array)) {
            return new ArraySeq($array);
        } elseif ($array instanceof PhpTraversable) {
            return new TraversableSeq($array);
        } else {
            throw new \InvalidArgumentException('Seq::fromArray() needs to array or \Traversable.');
        }
    }

    /**
     * 末尾に要素を追加する
     *
     * @param mixed $that
     * @return Seq
     */
    abstract public function append($that);

    /**
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param callable $f
     * @return mixed
     */
    abstract public function fold($z, callable $f);

    /**
     * 先頭に要素を追加する
     *
     * @param mixed $that
     * @return Seq
     */
    abstract public function prepend($that);

    /**
     * 逆順にした Seq を返す
     *
     * @return Seq
     */
    abstract public function reverse();

    /**
     * Map に変換する
     *
     * $key に string が渡された場合は各要素から $key に該当する要素|プロパティを探し、それをキーとする
     * $key に callable が渡された場合は各要素を引数として $key を実行し、それをキーとする
     *
     * @param string|callable $key
     * @return Map
     */
    abstract public function toMap($key);

}
