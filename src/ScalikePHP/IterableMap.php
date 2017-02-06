<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * A Map Implementation using iterable.
 */
class IterableMap extends Map
{

    /**
     * @var array
     */
    protected $array = null;

    /**
     * @var array
     */
    protected $assoc = null;

    /**
     * Constructor.
     *
     * @param iterable $iterable
     */
    public function __construct(iterable $iterable)
    {
        parent::__construct($iterable);
    }

    /**
     * @inheritdoc
     * @return Map
     */
    public function append($keyOrArray, $value = null)
    {
        if (is_array($keyOrArray)) {
            return is_array($this->values)
                ? Map::from(array_merge($this->values, $keyOrArray))
                : Map::from($this->mergeGenerator($this->values, $keyOrArray));
        } else {
            return $this->append([$keyOrArray, $value]);
        }
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
     * @return Map
     */
    public function filter(\Closure $p)
    {
        return Map::from($this->filterGenerator($this->values, $p));
    }

    /**
     * @inheritdoc
     * @return Map
     */
    public function flatMap(\Closure $f)
    {
        return Map::from($this->flatMapGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     * @throws \LogicException
     */
    public function flatten(): Map
    {
        throw new \LogicException("Map::flatten() has not supported");
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->values as $key => $value) {
            $z = $f($z, $value, $key);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function get($key): Option
    {
        return Option::fromArray(is_array($this->values) ? $this->values : $this->toAssoc(), $key);
    }

    /**
     * @inheritdoc
     */
    public function getOrElse($key, \Closure $default)
    {
        return $this->get($key)->getOrElse($default);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return is_array($this->values) ? $this->values : $this->toAssoc();
    }

    /**
     * @inheritdoc
     */
    public function keys(): Seq
    {
        return Seq::fromArray(array_keys(is_array($this->values) ? $this->values : $this->toAssoc()));
    }

    /**
     * @inheritdoc
     * @return Map
     */
    public function map(\Closure $f)
    {
        return Map::from($this->mapGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     */
    public function mapValues(\Closure $f)
    {
        return Map::from($this->mapValuesGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     */
    public function max(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        return [$key = $this->keys()->max(), $this->get($key)->get()];
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        $max = null;
        $res = [];
        foreach ($this->values as $key => $value) {
            $x = $f($value, $key);
            if ($max === null || $max < $x) {
                $max = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function min(): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        return [$key = $this->keys()->min(), $this->get($key)->get()];
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f): array
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        $min = null;
        $res = [];
        foreach ($this->values as $key => $value) {
            $x = $f($value, $key);
            if ($min === null || $min > $x) {
                $min = $x;
                $res = [$key, $value];
            }
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $this->buildArray();
        return $this->array;
    }

    /**
     * @inheritdoc
     */
    public function toAssoc(): array
    {
        if (is_array($this->values)) {
            return $this->values;
        } else {
            $this->buildAssoc();
            return $this->assoc;
        }
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return Seq::fromArray($this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function values(): Seq
    {
        return Seq::fromArray(array_values($this->toAssoc()));
    }

    /**
     * Build an array.
     *
     * @return void
     */
    protected function buildArray(): void
    {
        if ($this->array === null) {
            $this->array = [];
            foreach ($this->values as $key => $value) {
                $this->array[] = [$key, $value];
            }
        }
    }

    /**
     * Build an assoc.
     *
     * @return void
     */
    protected function buildAssoc(): void
    {
        if ($this->assoc === null) {
            $this->assoc = [];
            foreach ($this->values as $key => $value) {
                $this->assoc[$key] = $value;
            }
        }
    }

    /**
     * Create a Generator from iterable with filter.
     *
     * @param iterable $iterable
     * @param \Closure $p
     * @return \Iterator
     */
    protected function filterGenerator(iterable $iterable, \Closure $p): \Iterator
    {
        foreach ($iterable as $key => $value) {
            if ($p($value, $key)) {
                yield $key => $value;
            }
        }
    }

    /**
     * Create a Generator from iterable with flatmap.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Iterator
     * @throws \LogicException
     */
    protected function flatMapGenerator(iterable $iterable, \Closure $f): \Iterator
    {
        foreach ($iterable as $key => $value) {
            $iterable = $f($value, $key);
            if (is_iterable($iterable) === false) {
                throw new \LogicException("Closure should returns an iterable");
            }
            foreach ($iterable as $newKey => $newValue) {
                yield $newKey => $newValue;
            }
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Iterator
     */
    protected function mapGenerator(iterable $iterable, \Closure $f): \Iterator
    {
        foreach ($iterable as $key => $value) {
            [$newKey, $newValue] = $f($value, $key);
            yield $newKey => $newValue;
        }
    }

    /**
     * Create a Generator from iterable with map function.
     *
     * @param iterable $iterable
     * @param \Closure $f
     * @return \Iterator
     */
    protected function mapValuesGenerator(iterable $iterable, \Closure $f): \Iterator
    {
        foreach ($iterable as $key => $value) {
            yield $key => $f($value, $key);
        }
    }

    /**
     * Create a Generator from two iterables.
     *
     * @param iterable $a
     * @param iterable $b
     * @return \Iterator
     */
    protected function mergeGenerator(iterable $a, iterable $b): \Iterator
    {
        foreach ($a as $key => $value) {
            yield $key => $value;
        }
        foreach ($b as $key => $value) {
            yield $key => $value;
        }
    }

}
