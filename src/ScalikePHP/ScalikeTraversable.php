<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use BadMethodCallException;
use Closure;
use Generator;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

/**
 * Scala like Traversable Implementation.
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{
    // overrides
    public function each(Closure $f): void
    {
        foreach ($this->getRawIterable() as $key => $value) {
            $f($value, $key);
        }
    }

    // overrides
    public function exists(Closure $p): bool
    {
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                return true;
            }
        }
        return false;
    }

    // overrides
    public function filterNot(Closure $p): self
    {
        return $this->filter(fn ($value): bool => !$p($value));
    }

    // overrides
    public function find(Closure $p): Option
    {
        foreach ($this->getRawIterable() as $value) {
            if ($p($value)) {
                return Option::some($value);
            }
        }
        return Option::none();
    }

    // overrides
    public function forAll(Closure $p): bool
    {
        foreach ($this->getRawIterable() as $value) {
            if (!$p($value)) {
                return false;
            }
        }
        return true;
    }

    // overrides
    public function generate(Closure $f): Generator
    {
        foreach ($this->getRawIterable() as $k => $v) {
            yield from $f($v, $k);
        }
    }

    /**
     * @return iterable
     */
    abstract protected function getRawIterable(): iterable;

    /**
     * @param Closure|string $f
     * @return Closure
     */
    protected function groupByClosure($f): Closure
    {
        if (is_string($f)) {
            return fn ($value) => Option::from($value)->pick($f)->getOrElse(function () use ($f): void {
                throw new RuntimeException("Undefined index {$f}");
            });
        } elseif ($f instanceof Closure) {
            return $f;
        } else {
            $type = gettype($f);
            throw new InvalidArgumentException("`groupBy` needs a string or \\Closure. {$type} given.");
        }
    }

    // overrides
    public function head()
    {
        foreach ($this->getRawIterable() as $value) {
            return $value;
        }
        throw new LogicException('There is no value');
    }

    // overrides
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $value) {
            return Option::some($value);
        }
        return Option::none();
    }

    // overrides
    public function last()
    {
        return $this->takeRight(1)->toSeq()->head();
    }

    // overrides
    public function lastOption(): Option
    {
        return $this->takeRight(1)->toSeq()->headOption();
    }

    // overrides
    public function mkString(string $sep = ''): string
    {
        return implode($sep, $this->toArray());
    }

    // overrides
    public function nonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * PHP magic method: offsetSet.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException();
    }

    /**
     * PHP magic method: offsetUnset.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException();
    }

    // overrides
    public function sum()
    {
        return array_sum($this->toArray());
    }

    // overrides
    public function tail(): self
    {
        if ($this->isEmpty()) {
            throw new LogicException('Unsupported operation: tail of empty list');
        }
        return $this->drop(1);
    }
}
