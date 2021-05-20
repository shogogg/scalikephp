<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use ArrayAccess;
use ArrayIterator;
use Closure;
use JsonSerializable;
use LogicException;
use ScalikePHP\Implementations\ArrayMap;
use ScalikePHP\Implementations\ArraySeq;
use ScalikePHP\Implementations\ArraySupport;
use ScalikePHP\Implementations\OptionOps;
use Traversable;

/**
 * Scala like Some.
 */
final class Some extends Option
{
    use ArraySupport;
    use OptionOps;

    /**
     * Create a Some instance.
     *
     * @param mixed $value 値
     * @return \ScalikePHP\Some
     */
    public static function create($value): self
    {
        return new self($value);
    }

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\Some}.
     *
     * @param mixed $value 値
     */
    protected function __construct($value)
    {
        $this->setArray([$value]);
    }

    // overrides
    public function drop(int $n): Seq
    {
        return $n <= 0 ? $this->toSeq() : Seq::empty();
    }

    // overrides
    public function filter(Closure $p): Option
    {
        return $p($this->array[0]) ? $this : Option::none();
    }

    // overrides
    public function flatMap(Closure $f): Option
    {
        $option = $f($this->array[0]);
        if ($option instanceof Option) {
            return $option;
        } else {
            throw new LogicException('Closure should returns an Option');
        }
    }

    // overrides
    public function flatten(): Option
    {
        if ($this->array[0] instanceof Option) {
            return $this->array[0];
        } else {
            throw new LogicException('Element should be an Option');
        }
    }

    // overrides
    public function fold($z, Closure $op)
    {
        return $op($z, $this->array[0]);
    }

    // overrides
    public function get()
    {
        return $this->array[0];
    }

    // overrides
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->array);
    }

    // overrides
    public function getOrElse(Closure $default)
    {
        return $this->array[0];
    }

    // overrides
    public function getOrElseValue($default)
    {
        return $this->array[0];
    }

    // overrides
    public function groupBy($f): Map
    {
        $g = $this->groupByClosure($f);
        $assoc = [];
        foreach ($this->array as $value) {
            $x = $g($value);
            $assoc[$x] = $this->toSeq();
        }
        return new ArrayMap($assoc);
    }

    // overrides
    public function isDefined(): bool
    {
        return true;
    }

    // overrides
    public function jsonSerialize()
    {
        $value = $this->array[0];
        return $value instanceof JsonSerializable ? $value->jsonSerialize() : $value;
    }

    // overrides
    public function map(Closure $f): self
    {
        return new self($f($this->array[0]));
    }

    // overrides
    public function max()
    {
        return $this->array[0];
    }

    // overrides
    public function maxBy(Closure $f)
    {
        return $this->array[0];
    }

    // overrides
    public function min()
    {
        return $this->array[0];
    }

    // overrides
    public function minBy(Closure $f)
    {
        return $this->array[0];
    }

    // overrides
    public function orElse(Closure $alternative): Option
    {
        return $this;
    }

    // overrides
    public function orNull()
    {
        return $this->array[0];
    }

    /**
     * @return array|\ScalikePHP\Seq[]
     */
    public function partition(Closure $p): array
    {
        $value = $this->array[0];
        return $p($value)
            ? [Seq::from($value), Seq::empty()]
            : [Seq::empty(), Seq::from($value)];
    }

    // overrides
    public function sum()
    {
        return $this->array[0];
    }

    // overrides
    public function sumBy(Closure $f)
    {
        return $this->array[0];
    }

    // overrides
    public function pick($name): Option
    {
        $value = $this->array[0];
        if (is_array($value) || $value instanceof ArrayAccess) {
            return Option::fromArray($value, $name);
        } elseif (is_object($value) && (property_exists($value, $name) || method_exists($value, '__get'))) {
            return Option::from($value->{$name});
        } else {
            return Option::none();
        }
    }

    // overrides
    public function toArray(): array
    {
        return $this->array;
    }

    // overrides
    public function toSeq(): Seq
    {
        return new ArraySeq($this->array);
    }
}
