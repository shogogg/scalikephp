<?php
namespace ScalikePHP;

/**
 * Scala like Seq
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
    public static function emptySeq()
    {
        if (static::$empty === null) {
            static::$empty = new ArraySeq([]);
        }
        return static::$empty;
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
        } elseif ($array instanceof \Traversable) {
            return new TraversableSeq($array);
        } else {
            throw new \InvalidArgumentException('Seq::fromArray() needs to array or \Traversable.');
        }
    }

    /**
     * Constructor
     *
     * @param array|\Traversable $values
     */
    public function __construct($values)
    {
        $this->values = is_array($values) ? array_values($values) : $values;
    }

    /**
     * 末尾に要素を追加する
     *
     * @param mixed $that
     * @return Seq
     */
    abstract public function append($that);

    /**
     * 指定された値が含まれているかどうかを判定する
     *
     * @param mixed $elem
     * @return bool
     */
    abstract public function contains($elem);

    /**
     * 重複を排除した Seq を返す
     * 
     * @return Seq
     */
    abstract public function distinct();

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
     * 指定された関数の戻り値（または指定されたキーの値）を用いてソートされた Seq を返す
     *
     * @param string|\Closure $f
     * @return Seq
     */
    abstract public function sortBy($f);

    /**
     * Map に変換する
     *
     * $key に string が渡された場合は各要素から $key に該当する要素|プロパティを探し、それをキーとする
     * $key に \Closure が渡された場合は各要素を引数として $key を実行し、それをキーとする
     *
     * @param string|\Closure $key
     * @return Map
     */
    abstract public function toMap($key);

}
