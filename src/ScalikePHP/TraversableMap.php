<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\GeneratorIterator;
use ScalikePHP\Support\TraversableSupport;

/**
 * A Map Implementation using \Traversable.
 */
class TraversableMap extends Map
{

    use TraversableSupport;

    /**
     * Constructor.
     *
     * @param \Traversable $traversable
     */
    public function __construct(\Traversable $traversable)
    {
        $this->setTraversable($traversable);
    }

    /**
     * @inheritdoc
     */
    public function append($keyOrArray, $value = null)
    {
        $g = $this->mergeGenerator(
            $this->traversable,
            is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value]
        );
        return new TraversableMap($g, true);
    }

    /**
     * @inheritdoc
     */
    public function contains($key): bool
    {
        return array_key_exists($key, $this->toAssoc());
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        foreach ($this->getIterator() as $key => $value) {
            $f($value, $key);
        }
    }

    /**
     * @inheritdoc
     */
    public function exists(\Closure $p): bool
    {
        foreach ($this->getIterator() as $key => $value) {
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
        foreach ($this->getIterator() as $key => $value) {
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
        foreach ($this->getIterator() as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function forAll(\Closure $p): bool
    {
        foreach ($this->getIterator() as $key => $value) {
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
        return Option::fromArray($this->toAssoc(), $key);
    }

    /**
     * @inheritdoc
     */
    public function head()
    {
        foreach ($this->getIterator() as $key => $value) {
            return [$key, $value];
        }
        throw new \LogicException("There is no value");
    }

    /**
     * @inheritdoc
     */
    public function headOption(): Option
    {
        foreach ($this->getRawIterable() as $key => $value) {
            return Option::some([$key, $value]);
        }
        return Option::none();
    }

    /**
     * @inheritdoc
     */
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->toAssoc()));
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
        $this->compute();
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
        return new ArraySeq(array_values($this->toAssoc()));
    }

}
