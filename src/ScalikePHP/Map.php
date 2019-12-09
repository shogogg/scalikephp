<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use ScalikePHP\Support\ClosureIterator;

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
     * Create an instance from generator function.
     *
     * @param \Closure $f
     * @return \ScalikePHP\Map
     */
    final public static function create(\Closure $f): Map
    {
        return Map::fromTraversable(new ClosureIterator($f));
    }

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
        } elseif ($iterable instanceof \Traversable) {
            return Map::fromTraversable($iterable);
        } else {
            throw new \InvalidArgumentException('Map::from() needs to array or \Traversable.');
        }
    }

    /**
     * Create an instance from an iterator.
     *
     * @param \Traversable $traversable
     * @return \ScalikePHP\Map
     */
    final public static function fromTraversable(\Traversable $traversable): Map
    {
        return new TraversableMap($traversable);
    }

    /** {@inheritdoc} */
    public function each(\Closure $f): void
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $f($value, $key);
        }
    }

    /** {@inheritdoc} */
    public function exists(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /** {@inheritdoc} */
    public function filter(\Closure $p)
    {
        return static::create(function () use ($p): \Traversable {
            foreach ($this->getRawIterable() as $key => $value) {
                if ($p($value, $key)) {
                    yield $key => $value;
                }
            }
        });
    }

    /** {@inheritdoc} */
    public function find(\Closure $p): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return Option::some([$key, $value]);
            }
        }
        return Option::none();
    }

    /** {@inheritdoc} */
    public function flatMap(\Closure $f)
    {
        return Map::create(function () use ($f): \Generator {
            return $this->flatMapGenerator($f);
        });
    }

    /** {@inheritdoc} */
    public function groupBy($f): Map
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->getRawIterable() as $key => $value) {
            $x = $g($value);
            $assoc[$x] = isset($assoc[$x]) ? $assoc[$x] : [];
            $assoc[$x][$key] = $value;
        }
        foreach ($assoc as $key => $xs) {
            $assoc[$key] = new ArrayMap($xs);
        }
        return new ArrayMap($assoc);
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

    /** {@inheritdoc} */
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
    public function fold($z, \Closure $f)
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /** {@inheritdoc} */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if (!$p($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /** {@inheritdoc} */
    public function head()
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return [$key, $value];
        }
        throw new \LogicException("There is no value");
    }

    /** {@inheritdoc} */
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return Option::some([$key, $value]);
        }
        return Option::none();
    }

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

    /** {@inheritdoc} */
    public function jsonSerialize(): array
    {
        return $this->toAssoc();
    }

    /**
     * キーの一覧を Seq として取得する.
     *
     * @return Seq
     */
    abstract public function keys(): Seq;

    /** {@inheritdoc} */
    public function map(\Closure $f)
    {
        return Map::create(function () use ($f): \Generator {
            return $this->mapGenerator($f);
        });
    }

    /** {@inheritdoc} */
    public function mapValues(\Closure $f)
    {
        return Map::create(function () use ($f): \Generator {
            return $this->mapValuesGenerator($f);
        });
    }

    /** {@inheritdoc} */
    public function mkString(string $sep = ""): string
    {
        $f = function (array $x): string {
            return "{$x[0]} => {$x[1]}";
        };
        return $this->toSeq()->map($f)->mkString($sep);
    }

    /** {@inheritdoc} */
    public function max(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        return [$key = $this->keys()->max(), $this->get($key)->get()];
    }

    /** {@inheritdoc} */
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

    /** {@inheritdoc} */
    public function min(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        return [$key = $this->keys()->min(), $this->get($key)->get()];
    }

    /** {@inheritdoc} */
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

    /** {@inheritdoc} */
    public function sum()
    {
        throw new \LogicException("`Map::sum()` has not supported: Use `Map::sumBy()` instead");
    }

    /** {@inheritdoc} */
    public function sumBy(\Closure $f)
    {
        return $this->fold(0, $f);
    }

    /** {@inheritdoc} */
    public function takeRight(int $n): Map
    {
        if ($n > 0) {
            return new ArrayMap(array_slice($this->toAssoc(), 0 - $n, $n));
        } elseif ($n === 0) {
            return Map::emptyMap();
        } else {
            return $this;
        }
    }

    /** {@inheritdoc} */
    public function toArray(): array
    {
        return $this->toSeq()->toArray();
    }

    /**
     * Convert to an assoc.
     *
     * @return array
     */
    abstract public function toAssoc(): array;

    /** {@inheritdoc} */
    public function toGenerator(): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield $key => $value;
        }
    }

    /** {@inheritdoc} */
    public function toSeq(): Seq
    {
        return Seq::create(function (): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $key => $value) {
                yield $index++ => [$key, $value];
            }
        });
    }

    /**
     * Get the values as Seq.
     *
     * @return Seq
     */
    abstract public function values(): Seq;

    /**
     * Create a Generator from iterable with flatMap.
     *
     * @param \Closure $f
     * @return \Generator
     * @throws \LogicException
     */
    protected function flatMapGenerator(\Closure $f): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $iterable = $f($value, $key);
            if (is_iterable($iterable) === false) {
                throw new \LogicException("Closure should returns an iterable");
            }
            foreach ($iterable as $newKey => $newValue) {
                yield $newKey => $newValue;
            }
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param \Closure $f
     * @return \Generator
     */
    protected function mapGenerator(\Closure $f): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            [$newKey, $newValue] = $f($value, $key);
            yield $newKey => $newValue;
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param \Closure $f
     * @return \Generator
     */
    protected function mapValuesGenerator(\Closure $f): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield $key => $f($value, $key);
        }
    }

}
