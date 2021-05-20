<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

use Closure;
use Generator;
use LogicException;
use RuntimeException;
use ScalikePHP\ArrayMap;
use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;

/**
 * Map operations.
 *
 * @mixin \ScalikePHP\Map
 */
trait MapOps
{
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
    public function filter(Closure $p): Map
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
     * @noinspection PhpUnused
     */
    public function flatMap(Closure $f): Map
    {
        return self::from($this->flatMapGenerator($f));
    }

    /**
     * {@inheritdoc}
     */
    public function groupBy($f): Map
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
     * {@inheritdoc}
     */
    public function flatten(): Map
    {
        throw new LogicException('Map::flatten() has not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function fold($z, Closure $op)
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $z = $op($z, $value, $key);
        }
        return $z;
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpUnused
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
    public function getOrElse($key, Closure $default)
    {
        return $this->get($key)->getOrElse($default);
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpMissingReturnTypeInspection
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
     * @noinspection PhpUnused
     */
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return Option::some([$key, $value]);
        }
        return Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toAssoc();
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $f): Map
    {
        return self::fromTraversable($this->mapGenerator($f));
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpUnused
     */
    public function mapValues(Closure $f): Map
    {
        return self::fromTraversable($this->mapValuesGenerator($f));
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpUnused
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
     * @noinspection PhpUnused
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
     * @noinspection PhpUnused
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
     * @noinspection PhpUnused
     */
    public function sumBy(Closure $f)
    {
        return $this->fold(0, $f);
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function takeRight(int $n): Map
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
     * {@inheritdoc}
     * @noinspection PhpUnused
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
