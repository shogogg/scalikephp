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
use Traversable;

/**
 * Scala like None.
 */
final class None extends Option
{
    private static ?self $instance = null;

    /**
     * Get a None instance.
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

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function drop(int $n): Seq
    {
        return Seq::empty();
    }

    /**
     * {@inheritdoc}
     */
    public function each(Closure $f): void
    {
        // nothing to do.
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $p): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $p): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNot(Closure $p): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function find(Closure $p): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(Closure $f): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flatten(): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $p): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        throw new LogicException('None has no value.');
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse(Closure $default)
    {
        return $default();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElseValue($default)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRawIterable(): iterable
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function groupBy($f): Map
    {
        return Map::empty();
    }

    /**
     * {@inheritdoc}
     */
    public function head()
    {
        throw new LogicException('There is no value');
    }

    /**
     * {@inheritdoc}
     */
    public function headOption(): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDefined(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        throw new LogicException('There is no value');
    }

    /**
     * {@inheritdoc}
     */
    public function lastOption(): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $f): self
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        throw new LogicException('empty.max');
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(Closure $f)
    {
        throw new LogicException('empty.max');
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        throw new LogicException('empty.min');
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(Closure $f)
    {
        throw new LogicException('empty.min');
    }

    /**
     * {@inheritdoc}
     */
    public function mkString(string $sep = ''): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function nonEmpty(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        throw new OutOfBoundsException("Undefined offset: {$offset}");
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException();
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(Closure $b): Option
    {
        $option = $b();
        if ($option instanceof Option) {
            return $option;
        } else {
            throw new LogicException('Closure should returns an Option');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function orNull()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function pick($name): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function sumBy(Closure $f): int
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function take(int $n): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight(int $n): Option
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq(): Seq
    {
        return Seq::empty();
    }
}
