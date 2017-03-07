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
 * A Seq implementation using array.
 */
class ArrayMap extends Map
{

    use ArraySupport;

    /**
     * Constructor.
     *
     * @param mixed[] $assoc
     */
    public function __construct(array $assoc)
    {
        $this->setArray($assoc);
    }

    /**
     * @inheritdoc
     */
    public function append($keyOrArray, $value = null)
    {
        $assoc = array_merge(
            $this->array,
            is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value]
        );
        return new ArrayMap($assoc);
    }

    /**
     * @inheritdoc
     */
    public function contains($key): bool
    {
        return isset($this->array[$key]);
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        foreach ($this->array as $key => $value) {
            $f($value, $key);
        }
    }

    /**
     * @inheritdoc
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->array as $key => $value) {
            if ($p($value, $key)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function find(\Closure $p): Option
    {
        foreach ($this->array as $key => $value) {
            if ($p($value, $key)) {
                return Option::some([$key, $value]);
            }
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->array as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->array as $key => $value) {
            if (!$p($value, $key)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function get($key): Option
    {
        return Option::fromArray($this->array, $key);
    }

    /**
     * @inheritdoc
     */
    public function head()
    {
        foreach ($this->array as $key => $value) {
            return [$key, $value];
        }
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function headOption(): Option
    {
        foreach ($this->array as $key => $value) {
            return Option::some([$key, $value]);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->array));
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->toSeq()->toArray();
    }

    /**
     * @inheritdoc
     */
    public function toAssoc(): array
    {
        return $this->array;
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return new TraversableSeq($this->pairGenerator());
    }

    /**
     * @inheritdoc
     */
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->array));
    }

}
