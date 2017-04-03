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
 */
trait MapSupport
{

    use GeneralSupport;

    /**
     * @inheritdoc
     * @return Map
     * @see Map::drop()
     */
    public function drop(int $n): Map
    {
        return new TraversableMap($this->dropGenerator($this->getRawIterable(), $n));
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $f($value, $key);
        }
    }

    /**
     * @inheritdoc
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function find(\Closure $p): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if ($p($value, $key)) {
                return Option::some([$key, $value]);
            }
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     * @return Map
     * @see Map::filter()
     */
    public function filter(\Closure $p)
    {
        return new TraversableMap($this->filterGenerator($this->getRawIterable(), $p));
    }

    /**
     * @inheritdoc
     * @return Map
     * @see Map::flatMap()
     */
    public function flatMap(\Closure $f)
    {
        return new ArrayMap($this->flatMapAssoc($this->getRawIterable(), $f));
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $key => $value) {
            if (!$p($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function head()
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return [$key, $value];
        }
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return Option::some([$key, $value]);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     * @return Map
     * @see Map::map()
     */
    public function map(\Closure $f)
    {
        return new ArrayMap($this->mapAssoc($this->getRawIterable(), $f));
    }

    /**
     * @inheritdoc
     * @see Map::mapValues()
     */
    public function mapValues(\Closure $f) {
        return new TraversableMap($this->mapValuesGenerator($this->getIterator(), $f));
    }

    /**
     * @inheritdoc
     * @see Map::sumBy()
     */
    public function sumBy(\Closure $f)
    {
        return $this->fold(0, $f);
    }

    /**
     * @inheritdoc
     * @see Map::toArray()
     */
    public function toArray(): array
    {
        return $this->toSeq()->toArray();
    }

    /**
     * @inheritdoc
     * @see Map::toSeq()
     */
    public function toSeq(): Seq
    {
        return new TraversableSeq($this->pairGenerator());
    }

    /**
     * Crate a dropped generator.
     *
     * @param iterable $iterable
     * @param int $n
     * @return \Generator
     */
    private function dropGenerator(iterable $iterable, int $n): \Generator
    {
        $i = $n;
        foreach ($iterable as $key => $value) {
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
     * @param iterable $iterable
     * @param \Closure $p
     * @return \Generator
     */
    protected function filterGenerator(iterable $iterable, \Closure $p): \Generator
    {
        foreach ($iterable as $key => $value) {
            if ($p($value, $key)) {
                yield $key => $value;
            }
        }
    }

    /**
     * Create an assoc from iterable with flatmap.
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
     * Create a Generator from iterable with flatmap.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Generator
     * @throws \LogicException
     */
    private function flatMapGenerator(iterable $iterable, \Closure $f): \Generator
    {
        foreach ($iterable as $key => $value) {
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
     * Create an assoc from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return array
     */
    protected function mapAssoc(iterable $iterable, \Closure $f): array
    {
        return iterator_to_array($this->mapGenerator($iterable, $f));
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Generator
     */
    private function mapGenerator(iterable $iterable, \Closure $f): \Generator
    {
        foreach ($iterable as $key => $value) {
            [$newKey, $newValue] = $f($value, $key);
            yield $newKey => $newValue;
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Generator
     */
    protected function mapValuesGenerator(iterable $iterable, \Closure $f): \Generator
    {
        foreach ($iterable as $key => $value) {
            yield $key => $f($value, $key);
        }
    }

    /**
     * Create a Generator from two iterables.
     *
     * @param iterable $a
     * @param iterable $b
     * @return \Generator
     */
    private function mergeGenerator(iterable $a, iterable $b): \Generator
    {
        foreach ($a as $key => $value) {
            yield $key => $value;
        }
        foreach ($b as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * Returns a generator that yields key & value pairs.
     *
     * @return \Generator
     */
    private function pairGenerator(): \Generator
    {
        foreach ($this->getRawIterable() as $key => $value) {
            yield [$key, $value];
        }
    }

}
