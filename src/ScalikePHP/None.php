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
use EmptyIterator;
use LogicException;
use OutOfBoundsException;
use ScalikePHP\Implementations\OptionOps;
use Traversable;

/**
 * Scala like None.
 */
final class None extends Option
{
    use OptionOps;

    private static ?self $instance = null;

    /**
     * Returns the None instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // overrides
    public function count(): int
    {
        return 0;
    }

    // overrides
    public function drop(int $n): Seq
    {
        return Seq::empty();
    }

    // overrides
    public function each(Closure $f): void
    {
        // nothing to do.
    }

    // overrides
    public function exists(Closure $p): bool
    {
        return false;
    }

    // overrides
    public function filter(Closure $p): self
    {
        return $this;
    }

    // overrides
    public function filterNot(Closure $p): self
    {
        return $this;
    }

    // overrides
    public function find(Closure $p): Option
    {
        return $this;
    }

    // overrides
    public function flatMap(Closure $f): self
    {
        return $this;
    }

    // overrides
    public function flatten(): self
    {
        return $this;
    }

    // overrides
    public function fold($z, Closure $op)
    {
        return $z;
    }

    // overrides
    public function forAll(Closure $p): bool
    {
        return true;
    }

    // overrides
    public function get()
    {
        throw new LogicException('None has no value.');
    }

    // overrides
    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }

    // overrides
    public function getOrElse(Closure $default)
    {
        return $default();
    }

    // overrides
    public function getOrElseValue($default)
    {
        return $default;
    }

    // overrides
    protected function getRawIterable(): iterable
    {
        return [];
    }

    // overrides
    public function groupBy($f): Map
    {
        return Map::empty();
    }

    // overrides
    public function head()
    {
        throw new LogicException('There is no value');
    }

    // overrides
    public function headOption(): Option
    {
        return $this;
    }

    // overrides
    public function isDefined(): bool
    {
        return false;
    }

    // overrides
    public function isEmpty(): bool
    {
        return true;
    }

    // overrides
    public function jsonSerialize()
    {
        return null;
    }

    // overrides
    public function last()
    {
        throw new LogicException('There is no value');
    }

    // overrides
    public function lastOption(): Option
    {
        return $this;
    }

    // overrides
    public function map(Closure $f): self
    {
        return $this;
    }

    // overrides
    public function max()
    {
        throw new LogicException('empty.max');
    }

    // overrides
    public function maxBy(Closure $f)
    {
        throw new LogicException('empty.max');
    }

    // overrides
    public function min()
    {
        throw new LogicException('empty.min');
    }

    // overrides
    public function minBy(Closure $f)
    {
        throw new LogicException('empty.min');
    }

    // overrides
    public function mkString(string $sep = ''): string
    {
        return '';
    }

    // overrides
    public function nonEmpty(): bool
    {
        return false;
    }

    /**
     * PHP magic method: offsetExists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return false;
    }

    /**
     * PHP magic method: offsetGet.
     *
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        throw new OutOfBoundsException("Undefined offset: {$offset}");
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
    public function orElse(Closure $alternative): Option
    {
        $option = $alternative();
        if ($option instanceof Option) {
            return $option;
        } else {
            throw new LogicException('Closure should returns an Option');
        }
    }

    // overrides
    public function orNull()
    {
        return null;
    }

    /**
     * @return array|\ScalikePHP\Seq[]
     */
    public function partition(Closure $p): array
    {
        return [Seq::empty(), Seq::empty()];
    }

    // overrides
    public function pick($name): Option
    {
        return $this;
    }

    // overrides
    public function size(): int
    {
        return 0;
    }

    // overrides
    public function sumBy(Closure $f): int
    {
        return 0;
    }

    // overrides
    public function take(int $n): Option
    {
        return $this;
    }

    // overrides
    public function takeRight(int $n): Option
    {
        return $this;
    }

    // overrides
    public function toArray(): array
    {
        return [];
    }

    // overrides
    public function toSeq(): Seq
    {
        return Seq::empty();
    }
}
