<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP\Support;

use ScalikePHP\ArrayMap;
use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;
use ScalikePHP\TraversableMap;
use ScalikePHP\TraversableSeq;

/**
 * Support functions for Map.
 *
 * @mixin \ScalikePHP\ScalikeTraversable
 */
trait MapSupport
{

    use GeneralSupport;

    /** {@inheritdoc} */
    public function drop(int $n): Map
    {
        return new TraversableMap(function () use ($n): \Generator {
            $i = $n;
            foreach ($this->getRawIterable() as $key => $value) {
                if ($i <= 0) {
                    yield $key => $value;
                } else {
                    --$i;
                }
            }
        });
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
    public function filter(\Closure $p)
    {
        return new TraversableMap(function () use ($p): \Generator {
            return $this->filterGenerator($p);
        });
    }

    /** {@inheritdoc} */
    public function flatMap(\Closure $f)
    {
        return new TraversableMap(function () use ($f): \Generator {
            return $this->flatMapGenerator($f);
        });
    }

    /** {@inheritdoc} */
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

    /** {@inheritdoc} */
    public function map(\Closure $f)
    {
        return new TraversableMap(function () use ($f): \Generator {
            return $this->mapGenerator($f);
        });
    }

    /** {@inheritdoc} */
    public function mapValues(\Closure $f) {
        return new TraversableMap(function () use ($f): \Generator {
            return $this->mapValuesGenerator($f);
        });
    }

    /** {@inheritdoc} */
    public function sumBy(\Closure $f)
    {
        return $this->fold(0, $f);
    }

    /** {@inheritdoc} */
    public function toArray(): array
    {
        return $this->toSeq()->toArray();
    }

    /** {@inheritdoc} */
    public function toSeq(): Seq
    {
        return new TraversableSeq(function (): \Generator {
            $index = 0;
            foreach ($this->getRawIterable() as $key => $value) {
                yield $index++ => [$key, $value];
            }
        });
    }

    /**
     * Crate a dropped generator.
     *
     * @param int $n
     * @return \Generator
     */
    private function dropGenerator(int $n): \Generator
    {
        $i = $n;
        foreach ($this->getRawIterable() as $key => $value) {
            if ($i <= 0) {
                yield $key => $value;
            } else {
                --$i;
            }
        }
    }

    /**
     * Create a Generator from iterable with filter.
     *
     * @param \Closure $p
     * @return \Generator
     */
    protected function filterGenerator(\Closure $p): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                yield $key => $value;
            }
        }
    }

    /**
     * Create an assoc from iterable with flatMap.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return array
     * @throws \LogicException
     */
    protected function flatMapAssoc(iterable $iterable, \Closure $f): array
    {
        return iterator_to_array($this->flatMapGenerator($iterable, $f));
    }

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
