<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

/**
 * Scala like Map.
 */
abstract class Map extends ScalikeTraversable
{

    /**
     * An empty Map instance.
     *
     * @var Map
     */
    private static $empty = null;

    /**
     * Get an empty Map instance.
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
     * Create a Map instance from an iterable.
     *
     * @param iterable|null $iterable
     * @return Map
     * @throws \InvalidArgumentException
     */
    public static function from(?iterable $iterable): Map
    {
        if ($iterable === null) {
            return static::emptyMap();
        } elseif (is_array($iterable)) {
            return empty($iterable) ? static::emptyMap() : new ArrayMap($iterable);
        } elseif (is_iterable($iterable)) {
            return new TraversableMap($iterable);
        } else {
            throw new \InvalidArgumentException('Map::from() needs to array or \Traversable.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function groupByElement($value, $key): ScalikeTraversable
    {
        return new ArrayMap([$key => $value]);
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
     * @inheritdoc
     */
    public function flatten()
    {
        throw new \LogicException("Map::flatten() has not supported");
    }

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
    public function getOrElse($key, \Closure $default)
    {
        return $this->get($key)->getOrElse($default);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toAssoc();
    }

    /**
     * @inheritdoc
     */
    public function mkString(string $sep = ""): string
    {
        $f = function (array $x): string {
            return "{$x[0]} => {$x[1]}";
        };
        return $this->toSeq()->map($f)->mkString($sep);
    }

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
    abstract public function mapValues(\Closure $f): Map;

    /**
     * @inheritdoc
     */
    public function max(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        return [$key = $this->keys()->max(), $this->get($key)->get()];
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        $max = null;
        $res = [];
        foreach ($this->getRawIterable() as $key => $value) {
            $x = $f($value, $key);
            if ($max === null || $max < $x) {
                $max = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function min(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        return [$key = $this->keys()->min(), $this->get($key)->get()];
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        $min = null;
        $res = [];
        foreach ($this->getRawIterable() as $key => $value) {
            $x = $f($value, $key);
            if ($min === null || $min > $x) {
                $min = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function sum()
    {
        throw new \LogicException("`Map::sum()` has not supported: Use `Map::sumBy()` instead");
    }

    /**
     * @inheritdoc
     */
    public function take(int $n): Map
    {
        return new ArrayMap(array_slice($this->toAssoc(), 0, $n));
    }

    /**
     * @inheritdoc
     */
    public function takeRight(int $n): Map
    {
        return new ArrayMap(array_slice($this->toAssoc(), 0 - $n, $n));
    }

    /**
     * Convert to an assoc.
     *
     * @return array
     */
    abstract public function toAssoc(): array;

    /**
     * @inheritdoc
     */
    public function toGenerator(): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * Get the values as Seq.
     *
     * @return Seq
     */
    abstract public function values(): Seq;

}
