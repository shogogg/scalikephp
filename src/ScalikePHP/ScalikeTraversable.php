<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

use ScalikePHP\Support\GeneralSupport;

/**
 * Scala like Traversable Implementation.
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    use GeneralSupport;

    /**
     * @inheritdoc
     * @see ScalikeTraversable::each()
     */
    public function each(\Closure $f): void
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $f($value);
        }
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::exists()
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function filterNot(\Closure $p)
    {
        return $this->filter(function ($value) use ($p) {
            return !$p($value);
        });
    }

    /**
     * @inheritdoc
     * @see ScalikeTraversable::find()
     */
    public function find(\Closure $p): Option
    {
        foreach ($this->getRawIterable() as $value) {
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
        foreach ($this->getRawIterable() as $value) {
            if (!$p($value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function groupBy($f): Map
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->getRawIterable() as $key => $value) {
            $k = $g($value);
            $assoc[$k] = isset($assoc[$k]) ? $assoc[$k]->append([$key => $value]) : $this->groupByElement($value, $key);
        }
        return new ArrayMap($assoc);
    }

    /**
     * Generate a Closure for `groupBy`.
     *
     * @param string|\Closure $f
     * @return \Closure
     */
    protected function groupByClosure($f): \Closure
    {
        if (is_string($f)) {
            return function ($value) use ($f) {
                return Option::from($value)->pick($f)->getOrElse(function () use ($f): void {
                    throw new \RuntimeException("Undefined index {$f}");
                });
            };
        } elseif ($f instanceof \Closure) {
            return $f;
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("`groupBy` needs a string or \\Closure. {$type} given.");
        }
    }

    /**
     * Create new element for `groupBy`.
     *
     * @param mixed $value
     * @param mixed $key
     * @return ScalikeTraversable
     */
    abstract protected function groupByElement($value, $key): ScalikeTraversable;

    /**
     * @inheritdoc
     * @see ScalikeTraversable::head()
     */
    public function head()
    {
        foreach ($this->getRawIterable() as $value) {
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
        foreach ($this->getRawIterable() as $value) {
            return Option::some($value);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        return $this->takeRight(1)->toSeq()->head();
    }

    /**
     * @inheritdoc
     */
    public function lastOption(): Option
    {
        return $this->takeRight(1)->toSeq()->headOption();
    }

    /**
     * @inheritdoc
     */
    public function mkString(string $sep = ""): string
    {
        return implode($sep, $this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function nonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException;
    }

    /**
     * @inheritdoc
     */
    public function sum()
    {
        return array_sum($this->toArray());
    }

}
