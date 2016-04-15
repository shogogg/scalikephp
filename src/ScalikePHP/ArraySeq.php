<?php
namespace ScalikePHP;

/**
 * A Seq Implementation using PHP array
 */
class ArraySeq extends Seq
{

    /**
     * Constructor
     *
     * @param array $values 値
     */
    public function __construct(array $values)
    {
        $this->values = array_values($values);
    }

    /**
     * {@inheritdoc}
     */
    public function append($that)
    {
        if (is_array($that)) {
            return new ArraySeq(array_merge($this->toArray(), $that));
        } elseif ($that instanceof ScalikeTraversable || method_exists($that, 'toArray')) {
            return new ArraySeq(array_merge($this->toArray(), $that->toArray()));
        } elseif ($that instanceof \Traversable) {
            return new ArraySeq(array_merge($this->toArray(), Seq::fromArray($that)->toArray()));
        } else {
            return new ArraySeq(array_merge($this->toArray(), [$that]));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function contains($elem)
    {
        return in_array($elem, $this->values, true);
    }

    /**
     * {@inheritdoc}
     */
    public function distinct()
    {
        return new ArraySeq(array_values(array_unique($this->toArray())));
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f)
    {
        $values = [];
        foreach ($this->values as $x) {
            if (call_user_func($f, $x)) {
                $values[] = $x;
            }
        }
        return new ArraySeq($values);
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $f)
    {
        $values = [];
        foreach ($this->values as $x) {
            $result = call_user_func($f, $x);
            if (is_array($result)) {
                $values = array_merge($values, $result);
            } elseif ($result instanceof ScalikeTraversable || method_exists($result, 'toArray')) {
                $values = array_merge($values, $result->toArray());
            } elseif ($result instanceof \Traversable) {
                $values = array_merge($values, Seq::fromArray($result)->toArray());
            } else {
                throw new \InvalidArgumentException('$f should returns a Traversable or an array.');
            }
        }
        return new ArraySeq($values);
    }

    /**
     * {@inheritdoc}
     */
    public function fold($z, callable $f)
    {
        foreach ($this->values as $x) {
            $z = call_user_func($f, $z, $x);
        }
        return $z;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f)
    {
        return new ArraySeq(array_map($f, $this->values));
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.max');
        }
        return max($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(callable $f)
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.max');
        }
        $max = null;
        $res = null;
        foreach ($this->values as $value) {
            $x = call_user_func($f, $value);
            if ($max === null || $max < $x) {
                $max = $x;
                $res = $value;
            }
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.min');
        }
        return min($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(callable $f)
    {
        if ($this->isEmpty()) {
            throw new \RuntimeException('empty.min');
        }
        $min = null;
        $res = null;
        foreach ($this->values as $value) {
            $x = call_user_func($f, $value);
            if ($min === null || $min > $x) {
                $min = $x;
                $res = $value;
            }
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function prepend($that)
    {
        if (is_array($that)) {
            return new ArraySeq(array_merge($that, $this->toArray()));
        } elseif ($that instanceof ScalikeTraversable || method_exists($that, 'toArray')) {
            return new ArraySeq(array_merge($that->toArray(), $this->toArray()));
        } elseif ($that instanceof \Traversable) {
            return new ArraySeq(array_merge(Seq::fromArray($that)->toArray(), $this->toArray()));
        } else {
            return new ArraySeq(array_merge([$that], $this->toArray()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return new ArraySeq(array_reverse($this->toArray()));
    }

    /**
     * {@inheritdoc}
     */
    public function sortBy($f)
    {
        $array_for_sort = [];
        if (is_string($f)) {
            foreach ($this->values as $value) {
                $array_for_sort[] = Option::fromArray($value, $f)->getOrNull();
            }
        } elseif (is_callable($f)) {
            foreach ($this->values as $value) {
                $array_for_sort[] = $f($value);
            }
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("Seq::sortWith() needs a string or callable. {$type} given.");
        }
        $array_for_new_seq = $this->toArray();
        array_multisort($array_for_sort, SORT_ASC, $array_for_new_seq);
        return Seq::fromArray($array_for_new_seq);
    }

    /**
     * {@inheritdoc}
     */
    public function toMap($key)
    {
        $array = [];
        if (is_string($key)) {
            foreach ($this->values as $x) {
                $k = Option::from($x)->pick($key)->getOrThrow(new \RuntimeException("Undefined index {$key}"));
                $array[$k] = $x;
            }
        } elseif (is_callable($key)) {
            foreach ($this->values as $x) {
                $k = call_user_func($key, $x);
                $array[$k] = $x;
            }
        } else {
            $type = gettype($key);
            throw new \InvalidArgumentException("Seq::toMap() needs a string or callable. {$type} given.");
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq()
    {
        return $this;
    }

}
