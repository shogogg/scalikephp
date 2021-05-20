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
use ScalikePHP\Support\MapBuilder;

/**
 * Scala like Map.
 */
abstract class Map extends ScalikeTraversable
{
    use MapBuilder;

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $p): self
    {
        return self::from($this->filterGenerator($p));
    }

    /**
     * {@inheritdoc}
     */
    public function find(Closure $p): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return Option::some([$key, $value]);
            }
        }
        return Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(Closure $f): self
    {
        return self::from($this->flatMapGenerator($f));
    }

    /**
     * {@inheritdoc}
     */
    public function groupBy($f): self
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->getRawIterable() as $key => $value) {
            $x = $g($value);
            $assoc[$x] ??= [];
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
     * @param null|iterable $iterable
     * @throws InvalidArgumentException
     * @return \ScalikePHP\MutableMap
     * @noinspection PhpUnused
     */
    public static function mutable(?iterable $iterable): MutableMap
    {
        if ($iterable === null) {
            return new MutableMap([]);
        } elseif (is_iterable($iterable)) {
            return new MutableMap($iterable);
        } else {
            throw new InvalidArgumentException('Map::mutable() needs to array or \Traversable.');
        }
    }

    /**
     * 要素を追加する.
     *
     * @param array|Map|string $keyOrArray
     * @param mixed $value
     * @return static
     */
    abstract public function append($keyOrArray, $value = null): self;

    /**
     * 指定されたキーが存在するかどうかを判定する.
     *
     * @param int|string $key
     * @return bool
     */
    abstract public function contains($key): bool;

    /**
     * {@inheritdoc}
     */
    public function flatten(): self
    {
        throw new LogicException('Map::flatten() has not supported');
    }

    /**
     * 要素を順番に処理してたたみ込む.
     *
     * @param mixed $z
     * @param Closure $f
     * @return mixed
     */
    public function fold($z, Closure $f)
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if (!$p($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function head()
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return [$key, $value];
        }
        throw new LogicException('There is no value');
    }

    /**
     * {@inheritdoc}
     */
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
     * @return \ScalikePHP\Option
     */
    abstract public function get($key): Option;

    /**
     * 要素を取得する, 要素が存在しない場合は $default を返す.
     *
     * @param mixed $key
     * @param Closure $default
     * @return mixed
     */
    public function getOrElse($key, Closure $default)
    {
        return $this->get($key)->getOrElse($default);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toAssoc();
    }

    /**
     * キーの一覧を Seq として取得する.
     *
     * @return \ScalikePHP\Seq
     */
    abstract public function keys(): Seq;

    /**
     * {@inheritdoc}
     */
    public function map(Closure $f): self
    {
        return self::fromTraversable($this->mapGenerator($f));
    }

    /**
     * 値を変換した新しいインスタンスを返す.
     *
     * @param Closure $f
     * @return \ScalikePHP\Map
     */
    public function mapValues(Closure $f): self
    {
        return self::fromTraversable($this->mapValuesGenerator($f));
    }

    /**
     * {@inheritdoc}
     */
    public function mkString(string $sep = ''): string
    {
        $f = fn (array $x): string => "{$x[0]} => {$x[1]}";
        return $this->toSeq()->map($f)->mkString($sep);
    }

    /**
     * {@inheritdoc}
     */
    public function max(): array
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.max');
        }
        return [$key = $this->keys()->max(), $this->get($key)->get()];
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.max');
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
     * {@inheritdoc}
     */
    public function min(): array
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.min');
        }
        return [$key = $this->keys()->min(), $this->get($key)->get()];
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('empty.min');
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
     * {@inheritdoc}
     *
     * @return \ScalikePHP\Map[]
     */
    public function partition(Closure $p): array
    {
        $a = [];
        $b = [];
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value)) {
                $a[$key] = $value;
            } else {
                $b[$key] = $value;
            }
        }
        return [new ArrayMap($a), new ArrayMap($b)];
    }

    /**
     * {@inheritdoc}
     */
    public function sum()
    {
        throw new LogicException('`Map::sum()` has not supported: Use `Map::sumBy()` instead');
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
        if ($n > 0) {
            return new ArrayMap(array_slice($this->toAssoc(), 0 - $n, $n));
        } elseif ($n === 0) {
            return self::empty();
        } else {
            return $this;
        }
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function toGenerator(): Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq(): Seq
    {
        return Seq::create(function (): Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $key => $value) {
                yield $index++ => [$key, $value];
            }
        });
    }

    /**
     * Get the values as Seq.
     *
     * @return \ScalikePHP\Seq
     */
    abstract public function values(): Seq;

    /**
     * Create a Generator from iterable with flatMap.
     *
     * @param Closure $p
     * @return Generator
     */
    protected function filterGenerator(Closure $p): Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                yield $key => $value;
            }
        }
    }

    /**
     * Create a Generator from iterable with flatMap.
     *
     * @param Closure $f
     * @throws LogicException
     * @return Generator
     */
    protected function flatMapGenerator(Closure $f): Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $iterable = $f($value, $key);
            if (is_iterable($iterable) === false) {
                throw new LogicException('Closure should returns an iterable');
            }
            foreach ($iterable as $newKey => $newValue) {
                yield $newKey => $newValue;
            }
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param Closure $f
     * @return Generator
     */
    protected function mapGenerator(Closure $f): Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            [$newKey, $newValue] = $f($value, $key);
            yield $newKey => $newValue;
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param Closure $f
     * @return Generator
     */
    protected function mapValuesGenerator(Closure $f): Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield $key => $f($value, $key);
        }
    }
}
