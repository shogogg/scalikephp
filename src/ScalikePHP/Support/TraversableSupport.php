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
use ScalikePHP\ArraySeq;
use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;

/**
 * ScalikeTraversable implementation using an iterator(\Traversable).
 */
trait TraversableSupport
{

    use GeneralSupport;

    /**
     * @var mixed[]
     */
    private $array;

    /**
     * @var \Traversable
     */
    private $traversable;

    /**
     * @var bool
     */
    private $computed = false;

    /**
     * Set the traversable.
     *
     * @param \Traversable $traversable
     * @return void
     */
    protected function setTraversable(\Traversable $traversable): void
    {
        $this->traversable = $traversable instanceof \Generator
            ? new GeneratorIterator($traversable)
            : $traversable;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::count()
     */
    public function count()
    {
        return count($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::each()
     */
    public function each(\Closure $f): void
    {
        foreach ($this->traversable as $value) {
            $f($value);
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::exists()
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->traversable as $value) {
            if ($p($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::find()
     */
    public function find(\Closure $p): Option
    {
        foreach ($this->traversable as $value) {
            if ($p($value)) {
                return Option::some($value);
            }
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::forAll()
     */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->traversable as $value) {
            if (!$p($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::getIterator()
     */
    public function getIterator(): \Iterator
    {
        if ($this->traversable instanceof \Iterator) {
            return $this->traversable;
        } elseif ($this->traversable instanceof \IteratorAggregate) {
            return $this->traversable->getIterator();
        } else {
            return new \ArrayIterator($this->toArray());
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::head()
     */
    public function head()
    {
        foreach ($this->traversable as $value) {
            return $value;
        }
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::headOption()
     */
    public function headOption(): Option
    {
        foreach ($this->traversable as $value) {
            return Option::some($value);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::groupBy()
     */
    public function groupBy($f): Map
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->traversable as $key => $value) {
            $k = $g($value);
            $assoc[$k] = isset($assoc[$k]) ? $assoc[$k]->append([$key => $value]) : $this->groupByElement($value, $key);
        }
        return new ArrayMap($assoc);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::isEmpty()
     */
    public function isEmpty(): bool
    {
        return empty($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        $this->compute();
        return isset($this->array[$offset]);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetGet()
     */
    public function offsetGet($offset)
    {
        $this->compute();
        if (isset($this->array[$offset])) {
            return $this->array[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::size()
     */
    public function size(): int
    {
        return count($this->toArray());
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toArray()
     */
    public function toArray(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toSeq()
     */
    public function toSeq(): Seq
    {
        return new ArraySeq($this->toArray());
    }

    /**
     * Compute values.
     *
     * @return void
     */
    private function compute(): void
    {
        if ($this->computed === false) {
            $this->array = [];
            foreach ($this->traversable as $key => $value) {
                $this->array[$key] = $value;
            }
            $this->computed = true;
        }
    }

}
