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
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use ScalikePHP\Support\ClosureIterator;
use Traversable;

/**
 * Scala like Seq.
 */
abstract class Seq extends ScalikeTraversable
{
    private static ?self $empty = null;

    /**
     * Create an instance from generator function.
     *
     * @param Closure $f
     * @return \ScalikePHP\Seq
     */
    final public static function create(Closure $f): self
    {
        return self::fromTraversable(new ClosureIterator($f));
    }

    /**
     * Get an empty Seq instance.
     *
     * @return \ScalikePHP\Seq
     */
    final public static function empty(): self
    {
        if (self::$empty === null) {
            self::$empty = new ArraySeq([]);
        }
        return self::$empty;
    }

    /**
     * Get an empty Seq instance.
     *
     * @return \ScalikePHP\Seq
     * @deprecated
     */
    final public static function emptySeq(): self
    {
        return self::empty();
    }

    /**
     * Create a Seq instance from arguments.
     *
     * @param array $items
     * @return \ScalikePHP\Seq
     */
    final public static function from(...$items): self
    {
        return new ArraySeq($items);
    }

    /**
     * Create an instance from an iterable.
     *
     * @param null|iterable $iterable
     * @throws InvalidArgumentException
     * @return \ScalikePHP\Seq
     */
    final public static function fromArray(?iterable $iterable): self
    {
        if ($iterable === null) {
            return self::empty();
        } elseif (is_array($iterable)) {
            return empty($iterable) ? static::empty() : new ArraySeq((array)$iterable);
        } elseif ($iterable instanceof Traversable) {
            return self::fromTraversable($iterable);
        } else {
            throw new InvalidArgumentException('Seq::fromArray() needs to iterable');
        }
    }

    /**
     * Create an instance from an iterator.
     *
     * @param Traversable $traversable
     * @return \ScalikePHP\Seq
     */
    final public static function fromTraversable(Traversable $traversable): self
    {
        return new TraversableSeq($traversable);
    }

    /**
     * Create an instance from two iterables.
     *
     * @param iterable $a
     * @param iterable $b
     * @return \ScalikePHP\Seq
     */
    final public static function merge(iterable $a, iterable $b): self
    {
        return self::create(function () use ($a, $b) {
            $i = 0;
            foreach ($a as $x) {
                yield $i++ => $x;
            }
            foreach ($b as $x) {
                yield $i++ => $x;
            }
        });
    }

    /**
     * 末尾に要素を追加する.
     *
     * @param iterable $that
     * @return \ScalikePHP\Seq
     */
    public function append(iterable $that): self
    {
        return self::merge($this->getRawIterable(), $that);
    }

    /**
     * 遅延されている計算を行った結果を返す.
     *
     * @return \ScalikePHP\Seq
     */
    abstract public function computed(): self;

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
     * @return \ScalikePHP\Seq
     */
    public function distinct(): self
    {
        // `array_keys(array_count_values(...))` is faster than `array_unique(...)`
        return new ArraySeq(array_keys(array_count_values($this->toArray())));
    }

    /**
     * 指定された関数の戻り値を用いて重複を排除した Seq を返す.
     *
     * @param Closure $f
     * @return \ScalikePHP\Seq
     */
    public function distinctBy(Closure $f): self
    {
        return self::create(function () use ($f) {
            $keys = [];
            foreach ($this->getRawIterable() as $value) {
                $key = $f($value);
                if (!in_array($key, $keys, true)) {
                    $keys[] = $key;
                    yield $value;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $p): self
    {
        return static::create(function () use ($p): Traversable {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if ($p($value)) {
                    yield $index++ => $value;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(Closure $f): self
    {
        return self::create(function () use ($f): Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $key => $value) {
                $xs = $f($value, $key);
                if (is_iterable($xs) === false) {
                    throw new LogicException('Closure should returns an iterable');
                }
                foreach ($xs as $x) {
                    yield $index++ => $x;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function flatten(): self
    {
        return self::create(function (): Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if (is_iterable($value) === false) {
                    throw new LogicException('Closure should returns an iterable');
                }
                foreach ($value as $x) {
                    yield $index++ => $x;
                }
            }
        });
    }

    /**
     * 要素を順番に処理してたたみ込む
     *
     * @param mixed $z
     * @param Closure $f
     * @return mixed
     */
    public function fold($z, Closure $f)
    {
        foreach ($this->getRawIterable() as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /**
     * {@inheritdoc}
     */
    public function groupBy($f): Map
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->getRawIterable() as $value) {
            $x = $g($value);
            $assoc[$x] ??= [];
            $assoc[$x][] = $value;
        }
        foreach ($assoc as $key => $xs) {
            $assoc[$key] = new ArraySeq($xs);
        }
        return new ArrayMap($assoc);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $f): self
    {
        return self::create(function () use ($f): Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $key => $value) {
                yield $index++ => $f($value, $key);
            }
        });
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.max');
        }
        return max($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(Closure $f)
    {
        $maxValue = null;
        $maxElement = null;
        foreach ($this->getRawIterable() as $element) {
            $value = $f($element);
            if ($maxValue === null || $maxValue < $value) {
                $maxValue = $value;
                $maxElement = $element;
            }
        }
        return $maxElement;
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.min');
        }
        return min($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(Closure $f)
    {
        $minValue = null;
        $minElement = null;
        foreach ($this->getRawIterable() as $element) {
            $value = $f($element);
            if ($minValue === null || $minValue > $value) {
                $minValue = $value;
                $minElement = $element;
            }
        }
        return $minElement;
    }

    /**
     * 先頭に要素を追加する.
     *
     * @param iterable $that
     *
     * @return \ScalikePHP\Seq
     */
    public function prepend(iterable $that): self
    {
        return self::merge($that, $this->getRawIterable());
    }

    /**
     * 逆順にした Seq を返す.
     *
     * @return \ScalikePHP\Seq
     */
    public function reverse(): self
    {
        return new ArraySeq(array_reverse($this->toArray()));
    }

    /**
     * 指定された関数の戻り値（または指定されたキーの値）を用いてソートされた Seq を返す.
     *
     * @param Closure|string $f
     * @return \ScalikePHP\Seq
     */
    public function sortBy($f): self
    {
        $sortValues = [];
        if (is_string($f)) {
            foreach ($this->toArray() as $value) {
                $sortValues[] = Option::fromArray($value, $f)->orNull();
            }
        } elseif ($f instanceof Closure) {
            foreach ($this->toArray() as $value) {
                $sortValues[] = $f($value);
            }
        } else {
            $type = gettype($f);
            throw new InvalidArgumentException("Seq::sortWith() needs a string or \\Closure. {$type} given.");
        }
        $array = $this->toArray();
        array_multisort($sortValues, SORT_ASC, SORT_REGULAR, $array);
        return new ArraySeq($array);
    }

    /**
     * {@inheritdoc}
     */
    public function sumBy(Closure $f)
    {
        return $this->fold(0, $f);
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight(int $n): self
    {
        return new ArraySeq(array_slice($this->toArray(), 0 - $n, $n));
    }

    /**
     * {@inheritdoc}
     */
    public function toGenerator(): Generator
    {
        yield from $this->getRawIterable();
    }

    /**
     * Map に変換する.
     *
     * $key に string が渡された場合は各要素から $key に該当する要素|プロパティを探し、それをキーとする
     * $key に \Closure が渡された場合は各要素を引数として $key を実行し、それをキーとする
     *
     * @param Closure|string $key
     * @throws InvalidArgumentException
     * @return Map
     */
    public function toMap($key): Map
    {
        $assoc = [];
        if (is_string($key)) {
            foreach ($this->getRawIterable() as $value) {
                $k = Option::from($value)->pick($key)->getOrElse(function () use ($key): void {
                    throw new RuntimeException("Undefined index {$key}");
                });
                $assoc[$k] = $value;
            }
        } elseif ($key instanceof Closure) {
            foreach ($this->getRawIterable() as $value) {
                $assoc[$key($value)] = $value;
            }
        } else {
            $type = gettype($key);
            throw new InvalidArgumentException("Seq::toMap() needs a string or \\Closure. {$type} given.");
        }
        return new ArrayMap($assoc);
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq(): self
    {
        return $this;
    }
}
