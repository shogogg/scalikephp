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
            throw new \InvalidArgumentException('Map::fromArray() needs to array or \Traversable.');
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
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param callable $f
     * @return mixed
     */
    abstract public function fold($z, callable $f);

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