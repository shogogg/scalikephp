<?php
namespace ScalikePHP;

/**
 * Scala like Traversable Implementation
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    /**
     * 値
     * @var array|\Traversable
     */
    protected $values;

    /**
     * {@inheritdoc}
     */
    final public function count(): int
    {
        return $this->size();
    }

    /**
     * {@inheritdoc}
     */
    public function each(\Closure $f): void
    {
        array_walk($this->values, $f);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(\Closure $f): bool
    {
        foreach ($this->values as $value) {
            if (call_user_func($f, $value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function filterNot(\Closure $f)
    {
        return $this->filter(function ($x) use ($f) {
            return !call_user_func($f, $x);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function find(\Closure $f): Option
    {
        foreach ($this->values as $x) {
            if (call_user_func($f, $x)) {
                return Option::some($x);
            }
        }
        return Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function flatten()
    {
        return $this->flatMap(function ($x) {
            return $x;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(\Closure $f): bool
    {
        foreach ($this->values as $x) {
            if (!call_user_func($f, $x)) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function head()
    {
        foreach ($this->values as $x) {
            return $x;
        }
        throw new \RuntimeException('There is no values.');
    }

    /**
     * {@inheritdoc}
     */
    public function headOption(): Option
    {
        foreach ($this->values as $x) {
            return Option::some($x);
        }
        return Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function groupBy($f): Map
    {
        $array = [];
        if (is_string($f)) {
            foreach ($this->values as $x) {
                $k = Option::from($x)->pick($f)->getOrThrow(new \RuntimeException("Undefined index {$f}"));
                $array[$k] = isset($array[$k]) ? $array[$k]->append([$x]) : Seq::from($x);
            }
        } elseif (is_callable($f)) {
            foreach ($this->values as $x) {
                $k = call_user_func($f, $x);
                $array[$k] = isset($array[$k]) ? $array[$k]->append([$x]) : Seq::from($x);
            }
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("Seq::toMap() needs a string or \\Closure. {$type} given.");
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return $this->takeRight(1)->head();
    }

    /**
     * {@inheritdoc}
     */
    public function lastOption(): Option
    {
        return $this->takeRight(1)->headOption();
    }

    /**
     * 要素を文字列化して結合する
     *
     * @param string $sep
     * @return string
     */
    public function mkString($sep = ""): string
    {
        return implode($sep, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function nonEmpty(): bool
    {
        return $this->size() !== 0;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (isset($this->values[$offset])) {
            return $this->values[$offset];
        } else {
            throw new \OutOfBoundsException("Undefined offset: {$offset}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $x)
    {
        throw new \BadMethodCallException;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException;
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return count($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function take($n): Seq
    {
        return Seq::fromArray(array_slice($this->toArray(), 0, $n));
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight($n): Seq
    {
        return Seq::fromArray(array_slice($this->toArray(), 0 - $n));
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq(): Seq
    {
        return Seq::fromArray($this->values);
    }

}
