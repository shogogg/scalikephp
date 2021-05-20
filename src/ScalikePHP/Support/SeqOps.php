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
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use ScalikePHP\ArrayMap;
use ScalikePHP\ArraySeq;
use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;

/**
 * Seq operations.
 *
 * @mixin \ScalikePHP\Seq
 */
trait SeqOps
{
    /**
     * {@inheritdoc}
     */
    public function append(iterable $that): Seq
    {
        return self::merge($this->getRawIterable(), $that);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($elem): bool
    {
        return in_array($elem, $this->toArray(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function distinct(): Seq
    {
        // `array_keys(array_count_values(...))` is faster than `array_unique(...)`
        return new ArraySeq(array_keys(array_count_values($this->toArray())));
    }

    /**
     * {@inheritdoc}
     */
    public function distinctBy(Closure $f): Seq
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
    public function filter(Closure $p): Seq
    {
        return self::create(function () use ($p): Generator {
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
    public function flatMap(Closure $f): Seq
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
    public function flatten(): Seq
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
     * {@inheritdoc}
     */
    public function fold($z, Closure $op)
    {
        foreach ($this->getRawIterable() as $value) {
            $z = $op($z, $value);
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
    public function map(Closure $f): Seq
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
     * {@inheritdoc}
     *
     * @return array|\ScalikePHP\Seq[]
     */
    public function partition(Closure $p): array
    {
        $a = [];
        $b = [];
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                $a[] = $value;
            } else {
                $b[] = $value;
            }
        }
        return [new ArraySeq($a), new ArraySeq($b)];
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(iterable $that): Seq
    {
        return self::merge($that, $this->getRawIterable());
    }

    /**
     * {@inheritdoc}
     */
    public function reverse(): Seq
    {
        return new ArraySeq(array_reverse($this->toArray()));
    }

    /**
     * {@inheritdoc}
     */
    public function sortBy($f): Seq
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
    public function takeRight(int $n): Seq
    {
        return new ArraySeq(array_slice($this->toArray(), 0 - $n, $n));
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpUnused
     */
    public function toGenerator(): Generator
    {
        yield from $this->getRawIterable();
    }

    /**
     * {@inheritdoc}
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
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function toSeq(): Seq
    {
        return $this;
    }
}
