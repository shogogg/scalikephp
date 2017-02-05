<?php
namespace ScalikePHP;

/**
 * Scala like Map
 */
abstract class Map extends ScalikeTraversable
{

    /**
     * 空のマップ
     *
     * @var ArrayMap
     */
    private static $empty = null;

    /**
     * Get an empty Map instance
     *
     * @return Map
     */
    public static function emptyMap(): Map
    {
        if (static::$empty === null) {
            static::$empty = new ArrayMap([]);
        }
        return static::$empty;
    }

    /**
     * Create a Map instance from an array (or \Traversable)
     *
     * @param iterable|null $iterable
     * @return Map
     * @throws \InvalidArgumentException
     */
    public static function from(?iterable $iterable): Map
    {
        if ($iterable === null) {
            return static::emptyMap();
        } elseif (is_iterable($iterable)) {
            return new IterableMap($iterable);
        } else {
            throw new \InvalidArgumentException('Map::from() needs to array or \Traversable.');
        }
    }

    /**
     * Create a MutableMap instance from an iterable.
     *
     * @param iterable|null $iterable
     * @return MutableMap
     * @throws \InvalidArgumentException
     */
    public static function mutable(?iterable $iterable): MutableMap
    {
        if ($iterable === null) {
            return new MutableMap([]);
        } elseif (is_iterable($iterable)) {
            return new MutableMap($iterable);
        } else {
            throw new \InvalidArgumentException('Map::mutable() needs to array or \Traversable.');
        }
    }

    /**
     * 要素を追加する.
     *
     * @param string|array|Map $keyOrArray
     * @param mixed $value
     * @return Map
     */
    abstract public function append($keyOrArray, $value = null);

    /**
     * 指定されたキーが存在するかどうかを判定する.
     *
     * @param string $key
     * @return bool
     */
    abstract public function contains($key): bool;

    /**
     * 要素を順番に処理してたたみ込む.
     *
     * @param mixed $z
     * @param \Closure $f
     * @return mixed
     */
    abstract public function fold($z, \Closure $f);

    /**
     * 要素を取得する.
     *
     * @param mixed $key
     * @return Option
     */
    abstract public function get($key): Option;

    /**
     * 要素を取得する, 要素が存在しない場合は $default を返す.
     *
     * @param mixed $key
     * @param \Closure $default
     * @return mixed
     */
    abstract public function getOrElse($key, \Closure $default);

    /**
     * キーの一覧を Seq として取得する.
     *
     * @return Seq
     */
    abstract public function keys(): Seq;

    /**
     * 値を変換した Map を返す.
     *
     * @param \Closure $f
     * @return Map
     */
    abstract public function mapValues(\Closure $f);

    /**
     * 連想配列に変換する.
     *
     * @return array
     */
    abstract public function toAssoc(): array;

    /**
     * 値の一覧を Seq として取得する.
     *
     * @return Seq
     */
    abstract public function values(): Seq;

}
