<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * Scala like Traversable Implementation.
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    /** {@inheritdoc} */
    public function each(\Closure $f): void
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $f($value);
        }
    }

    /** {@inheritdoc} */
    public function exists(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                return true;
            }
        }
        return false;
    }

    /** {@inheritdoc} */
    public function filterNot(\Closure $p)
    {
        return $this->filter(function ($value) use ($p) {
            return !$p($value);
        });
    }

    /** {@inheritdoc} */
    public function find(\Closure $p): Option
    {
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                return Option::some($value);
            }
        }
        return Option::none();
    }

    /** {@inheritdoc} */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->getRawIterable() as $value) {
            if (!$p($value)) {
                return false;
            }
        }
        return true;
    }

    /** {@inheritdoc} */
    public function generate(\Closure $f): \Generator
    {
        foreach ($this->getRawIterable() as $k => $v) {
            foreach ($f($v, $k) as $gk => $gv) {
                yield $gk => $gv;
            }
        }
    }

    /**
     * Get raw iterable.
     *
     * @return iterable
     */
    abstract protected function getRawIterable(): iterable;

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

    /** {@inheritdoc} */
    public function head()
    {
        foreach ($this->getRawIterable() as $value) {
            return $value;
        }
        throw new \LogicException("There is no value");
    }

    /** {@inheritdoc} */
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $value) {
            return Option::some($value);
        }
        return Option::none();
    }

    /** {@inheritdoc} */
    public function last()
    {
        return $this->takeRight(1)->toSeq()->head();
    }

    /** {@inheritdoc} */
    public function lastOption(): Option
    {
        return $this->takeRight(1)->toSeq()->headOption();
    }

    /** {@inheritdoc} */
    public function mkString(string $sep = ""): string
    {
        return implode($sep, $this->toArray());
    }

    /** {@inheritdoc} */
    public function nonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /** {@inheritdoc} */
    public function offsetSet($offset, $value): void
    {
        throw new \BadMethodCallException;
    }

    /** {@inheritdoc} */
    public function offsetUnset($offset): void
    {
        throw new \BadMethodCallException;
    }

    /** {@inheritdoc} */
    public function sum()
    {
        return array_sum($this->toArray());
    }

}
