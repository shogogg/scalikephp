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
 * Scala like Seq.
 */
abstract class Seq extends ScalikeTraversable
{

    /**
     * 空の Seq.
     *
     * @var Seq
     */
    private static $empty = null;

    /**
     * Get an empty Seq instance.
     *
     * @return Seq
     */
    final public static function emptySeq(): Seq
    {
        if (static::$empty === null) {
            static::$empty = new ArraySeq([]);
        }
        return static::$empty;
    }

    /**
     * Create a Seq instance from arguments.
     *
     * @param mixed[] $items
     * @return Seq
     */
    final public static function from(... $items): Seq
    {
        return new ArraySeq($items);
    }

    /**
     * Create a Seq instance from an iterable.
     *
     * @param iterable|null $iterable
     * @return Seq
     * @throws \InvalidArgumentException
     */
    final public static function fromArray(?iterable $iterable): Seq
    {
        if ($iterable === null) {
            return static::emptySeq();
        } elseif (is_array($iterable)) {
            return empty($iterable) ? static::emptySeq() : new ArraySeq($iterable);
        } elseif (is_iterable($iterable)) {
            return new TraversableSeq($iterable);
        } else {
            throw new \InvalidArgumentException("Seq::fromArray() needs to iterable");
        }
    }

    /**
     * 末尾に要素を追加する.
     *
     * @param iterable $that
     * @return Seq
     */
    abstract public function append(iterable $that): Seq;

    /**
     * 指定された値が含まれているかどうかを判定する.
     *
     * @param mixed $elem
     * @return bool
     */
    public function contains($elem): bool
    {
        return in_array($elem, $this->toArray(), true);
    }

    /**
     * 重複を排除した Seq を返す.
     *
     * @return Seq
     */
    public function distinct(): Seq
    {
        // `array_keys(array_count_values(...))` is faster than `array_unique(...)`
        return new ArraySeq(array_keys(array_count_values($this->toArray())));
    }

    /**
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param \Closure $f
     * @return mixed
     */
    abstract public function fold($z, \Closure $f);

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        return max($this->toArray());
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function maxBy(\Closure $f)
    {
        $maxValue = null;
        $maxElement = null;
        foreach ($this->getIterator() as $element) {
            $value = $f($element);
            if ($maxValue === null || $maxValue < $value) {
                $maxValue = $value;
                $maxElement = $element;
            }
        }
        return $maxElement;
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        return min($this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        $minValue = null;
        $minElement = null;
        foreach ($this->getIterator() as $element) {
            $value = $f($element);
            if ($minValue === null || $minValue > $value) {
                $minValue = $value;
                $minElement = $element;
            }
        }
        return $minElement;
    }

    /**
     * 先頭に要素を追加する
     *
     * @param iterable $that
     * @return Seq
     */
    abstract public function prepend(iterable $that): Seq;

    /**
     * 逆順にした Seq を返す.
     *
     * @return Seq
     */
    public function reverse(): Seq
    {
        return new ArraySeq(array_reverse($this->toArray()));
    }

    /**
     * 指定された関数の戻り値（または指定されたキーの値）を用いてソートされた Seq を返す.
     *
     * @param string|\Closure $f
     * @return Seq
     */
    public function sortBy($f): Seq
    {
        $sortValues = [];
        if (is_string($f)) {
            foreach ($this->toArray() as $value) {
                $sortValues[] = Option::fromArray($value, $f)->orNull();
            }
        } elseif ($f instanceof \Closure) {
            foreach ($this->toArray() as $value) {
                $sortValues[] = $f($value);
            }
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("Seq::sortWith() needs a string or \\Closure. {$type} given.");
        }
        $array = $this->toArray();
        array_multisort($sortValues, SORT_ASC, SORT_REGULAR, $array);
        return new ArraySeq($array);
    }

    /**
     * @inheritdoc
     */
    public function takeRight(int $n): Seq
    {
        return new ArraySeq(array_slice($this->toArray(), 0 - $n, $n));
    }

    /**
     * @inheritdoc
     */
    public function toGenerator(): \Generator
    {
        foreach ($this->toArray() as $value) {
            yield $value;
        }
    }

    /**
     * Map に変換する
     *
     * $key に string が渡された場合は各要素から $key に該当する要素|プロパティを探し、それをキーとする
     * $key に \Closure が渡された場合は各要素を引数として $key を実行し、それをキーとする
     *
     * @param string|\Closure $key
     * @return Map
     */
    public function toMap($key): Map
    {
        $assoc = [];
        if (is_string($key)) {
            foreach ($this->getIterator() as $value) {
                $k = Option::from($value)->pick($key)->getOrElse(function () use ($key): void {
                    throw new \RuntimeException("Undefined index {$key}");
                });
                $assoc[$k] = $value;
            }
        } elseif ($key instanceof \Closure) {
            foreach ($this->getIterator() as $value) {
                $assoc[$key($value)] = $value;
            }
        } else {
            $type = gettype($key);
            throw new \InvalidArgumentException("Seq::toMap() needs a string or \\Closure. {$type} given.");
        }
        return new ArrayMap($assoc);
    }

}
