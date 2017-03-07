<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

/**
 * Scala like None.
 */
final class None extends Option
{

    /**
     * Singleton instance.
     *
     * @var None
     */
    private static $instance = null;

    /**
     * Get a None instance.
     *
     * @return None
     */
    public static function getInstance(): None
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        // nothing to do.
    }

    /**
     * @inheritdoc
     */
    public function exists(\Closure $p): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function filter(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function filterNot(\Closure $p)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function find(\Closure $p): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function flatMap(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function flatten(): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function forAll(\Closure $p): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        throw new \LogicException("None has no value.");
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \EmptyIterator;
    }

    /**
     * @inheritdoc
     */
    public function getOrCall(\Closure $f)
    {
        return $this->getOrElse($f);
    }

    /**
     * @inheritdoc
     */
    public function getOrElse(\Closure $default)
    {
        return $default();
    }

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function getOrThrow(\Exception $exception)
    {
        throw $exception;
    }

    /**
     * @inheritdoc
     */
    protected function getRawIterable(): iterable
    {
        return new \EmptyIterator;
    }

    /**
     * @inheritdoc
     */
    public function groupBy($f): Map
    {
        return Map::emptyMap();
    }

    /**
     * @inheritdoc
     */
    protected function groupByElement($value, $key): ScalikeTraversable
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function head()
    {
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function headOption(): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isDefined(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function lastOption(): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function max()
    {
        throw new \LogicException("empty.max");
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f)
    {
        throw new \LogicException("empty.max");
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        throw new \LogicException("empty.min");
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        throw new \LogicException("empty.min");
    }

    /**
     * @inheritdoc
     */
    public function mkString(string $sep = ""): string
    {
        return "";
    }

    /**
     * @inheritdoc
     */
    public function nonEmpty(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        throw new \OutOfBoundsException("Undefined offset: {$offset}");
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
    public function orElse(\Closure $b): Option
    {
        $option = $b();
        if ($option instanceof Option) {
            return $option;
        } else {
            throw new \LogicException("Closure should returns an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function orNull()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function orElseCall(\Closure $f): Option
    {
        return $f();
    }

    /**
     * @inheritdoc
     */
    public function pick($name): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function size(): int
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function take(int $n): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function takeRight(int $n): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return Seq::emptySeq();
    }

}
