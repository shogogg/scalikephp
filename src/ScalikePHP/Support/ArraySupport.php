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
 * ScalikeTraversable implementation using an array.
 */
trait ArraySupport
{

    use GeneralSupport;

    /**
     * @var mixed[]
     */
    private $array;

    /**
     * Set the array.
     *
     * @param mixed $array
     * @return void
     */
    protected function setArray(array $array): void
    {
        $this->array = $array;
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::count()
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::each()
     */
    public function each(\Closure $f): void
    {
        foreach ($this->array as $key => $value) {
            $f($value);
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::exists()
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->array as $value) {
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
        foreach ($this->array as $value) {
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
        foreach ($this->array as $value) {
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
    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::head()
     */
    public function head()
    {
        foreach ($this->array as $value) {
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
        foreach ($this->array as $value) {
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
        foreach ($this->array as $key => $value) {
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
        return empty($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        return isset($this->array[$offset]);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::offsetGet()
     */
    public function offsetGet($offset)
    {
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
        return count($this->array);
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::toArray()
     */
    public function toArray(): array
    {
        return $this->array;
    }

}
