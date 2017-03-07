<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\ArraySupport;

/**
 * Scala like Some.
 */
final class Some extends Option
{

    use ArraySupport;

    /**
     * Create a Some instance.
     *
     * @param mixed $value 値
     * @return Some
     */
    public static function create($value): Some
    {
        return new static($value);
    }

    /**
     * Constructor.
     *
     * @param mixed $value 値
     */
    protected function __construct($value)
    {
        $this->setArray([$value]);
    }

    /**
     * @inheritdoc
     */
    public function filter(\Closure $p): Option
    {
        return $p($this->array[0]) ? $this : Option::none();
    }

    /**
     * @inheritdoc
     */
    public function flatMap(\Closure $f): Option
    {
        $option = $f($this->array[0]);
        if ($option instanceof Option) {
            return $option;
        } else {
            throw new \LogicException("Closure should returns an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function flatten(): Option
    {
        if ($this->array[0] instanceof Option) {
            return $this->array[0];
        } else {
            throw new \LogicException("Element should be an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->array[0];
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
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function getOrElseValue($default)
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function getOrThrow(\Exception $exception)
    {
        return $this->array[0];
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
    public function isDefined(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $value = $this->array[0];
        return $value instanceof \JsonSerializable ? $value->jsonSerialize() : $value;
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $f): Some
    {
        return static::create($f($this->array[0]));
    }

    /**
     * @inheritdoc
     */
    public function max()
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f)
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function orElse(\Closure $b): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function orNull()
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function orElseCall(\Closure $f): Option
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function sum()
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function sumBy(\Closure $f)
    {
        return $this->array[0];
    }

    /**
     * @inheritdoc
     */
    public function pick($name): Option
    {
        $value = $this->array[0];
        if (is_array($value) || $value instanceof \ArrayAccess) {
            return Option::fromArray($value, $name);
        } elseif (is_object($value) && (property_exists($value, $name) || method_exists($value, '__get'))) {
            return Option::from($value->{$name});
        } else {
            return Option::none();
        }
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return new ArraySeq($this->array);
    }

}
