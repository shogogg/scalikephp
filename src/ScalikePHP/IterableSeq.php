<?php
namespace ScalikePHP;

/**
 * A Seq implementation using iterable.
 */
final class IterableSeq extends Seq
{

    /**
     * Constructor.
     *
     * @param iterable $values
     */
    public function __construct(iterable $values)
    {
        if (is_array($values)) {
            $this->array = array_values($values);
            parent::__construct($this->array);
        } else {
            parent::__construct($values);
        }
    }

    /**
     * @inheritdoc
     */
    public function append(iterable $that): Seq
    {
        return is_array($this->values) && is_array($that)
            ? Seq::fromArray(array_merge($this->values, $that))
            : Seq::fromArray($this->mergeGenerator($this->values, $that));
    }

    /**
     * @inheritdoc
     */
    public function contains($elem): bool
    {
        foreach ($this->values as $value) {
            if ($value === $elem) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function distinct(): Seq
    {
        return Seq::fromArray(array_keys(array_count_values($this->toArray())));
    }

    /**
     * @inheritdoc
     */
    public function filter(\Closure $p)
    {
        return Seq::fromArray($this->filterGenerator($this->values, $p));
    }

    /**
     * @inheritdoc
     * @throws \LogicException
     */
    public function flatMap(\Closure $f): Seq
    {
        return Seq::fromArray($this->flatMapGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->values as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $f)
    {
        return Seq::fromArray($this->mapGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.max");
        }
        return max($this->toArray());
    }

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function maxBy(\Closure $f)
    {
        return $this->map($f)->max();
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException("empty.min");
        }
        return min($this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        return $this->map($f)->min();
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return is_array($this->values) && is_array($that)
            ? Seq::fromArray(array_merge($that, $this->values))
            : Seq::fromArray($this->mergeGenerator($that, $this->values));
    }

    /**
     * @inheritdoc
     */
    public function reverse(): Seq
    {
        return Seq::fromArray(array_reverse($this->toArray()));
    }

    /**
     * @inheritdoc
     * @throws \InvalidArgumentException
     */
    public function sortBy($f): Seq
    {
        $array_for_sort = [];
        if (is_string($f)) {
            foreach ($this->values as $value) {
                $array_for_sort[] = Option::fromArray($value, $f)->orNull();
            }
        } elseif ($f instanceof \Closure) {
            foreach ($this->values as $value) {
                $array_for_sort[] = $f($value);
            }
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("Seq::sortWith() needs a string or \\Closure. {$type} given.");
        }
        $array_for_new_seq = $this->toArray();
        array_multisort($array_for_sort, SORT_ASC, SORT_REGULAR, $array_for_new_seq);
        return Seq::fromArray($array_for_new_seq);
    }

    /**
     * @inheritdoc
     */
    public function toMap($key): Map
    {
        $array = [];
        if (is_string($key)) {
            foreach ($this->values as $x) {
                $k = Option::from($x)->pick($key)->getOrThrow(new \RuntimeException("Undefined index {$key}"));
                $array[$k] = $x;
            }
        } elseif ($key instanceof \Closure) {
            foreach ($this->values as $x) {
                $k = $key($x);
                $array[$k] = $x;
            }
        } else {
            $type = gettype($key);
            throw new \InvalidArgumentException("Seq::toMap() needs a string or \\Closure. {$type} given.");
        }
        return Map::from($array);
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
        foreach ($iterable as $value) {
            if ($p($value)) {
                yield $value;
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
        foreach ($iterable as $value) {
            $iterable = $f($value);
            if (is_iterable($iterable) === false) {
                throw new \LogicException("Closure should returns an iterable");
            }
            foreach ($iterable as $x) {
                yield $x;
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
        foreach ($iterable as $value) {
            yield $f($value);
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
        foreach ($a as $value) {
            yield $value;
        }
        foreach ($b as $value) {
            yield $value;
        }
    }

}
