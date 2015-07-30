<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * Scala like Map
 */
abstract class Map extends ScalikeTraversable
{

    /**
     * Get an empty Map instance
     *
     * @return Map
     */
    public static function emptyMap()
    {
        $empty = null;
        if ($empty === null) {
            $empty = new ArrayMap([]);
        }
        return $empty;
    }

    /**
     * Create a Map instance from an array (or \Traversable)
     *
     * @param array|\Traversable $array
     * @return Map
     * @throws \InvalidArgumentException
     */
    public static function from($array)
    {
        if ($array === null) {
            return static::emptyMap();
        } elseif (is_array($array)) {
            return new ArrayMap($array);
        } elseif ($array instanceof PhpTraversable) {
            return new TraversableMap($array);
        } else {
            throw new \InvalidArgumentException('Map::from() needs to array or \Traversable.');
        }
    }

    /**
     * Create a MutableMap instance from an array (or \Traversable)
     *
     * @param array|\Traversable $array
     * @return MutableMap
     * @throws \InvalidArgumentException
     */
    public static function mutable($array)
    {
        if ($array === null) {
            return new MutableMap([]);
        } elseif (is_array($array) || $array instanceof PhpTraversable) {
            return new MutableMap($array);
        } else {
            throw new \InvalidArgumentException('Map::mutable() needs to array or \Traversable.');
        }
    }

    /**
     * 要素を追加する
     *
     * @param string|array|Map $keyOrArray
     * @param mixed $value
     * @return Map
     */
    abstract public function append($keyOrArray, $value = null);

    /**
     * 指定されたキーが存在するかどうかを判定する
     *
     * @param string $key
     * @return bool
     */
    abstract public function contains($key);

    /**
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param callable $f
     * @return mixed
     */
    abstract public function fold($z, callable $f);

    /**
     * 要素を取得する
     *
     * @param string $key
     * @return Option
     */
    abstract public function get($key);

    /**
     * 要素を取得する, 要素が存在しない場合は $default を返す
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    abstract public function getOrElse($key, $default);

    /**
     * キーの一覧を Seq として取得する
     *
     * @return Seq
     */
    abstract public function keys();

    /**
     * 値を変換した Map を返す
     *
     * @param callable $f
     * @return Map
     */
    abstract public function mapValues(callable $f);

    /**
     * 値の一覧を Seq として取得する
     *
     * @return Seq
     */
    abstract public function values();

}