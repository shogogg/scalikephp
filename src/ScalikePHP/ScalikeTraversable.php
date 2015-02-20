<?php
namespace ScalikePHP;

use ArrayIterator;
use BadMethodCallException;
use RuntimeException;

/**
 * Scala like Traversable imple
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    /**
     * 値
     * @var array
     */
    protected $values;

    /**
     * {@inheritdoc}
     */
    final public function count()
    {
        return $this->size();
    }

    /**
     * {@inheritdoc}
     */
    public function each(callable $f)
    {
        array_walk($this->values, $f);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(callable $f)
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
    public function filterNot(callable $f)
    {
        return $this->filter(function ($x) use ($f) {
            return !call_user_func($f, $x);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function find(callable $f)
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
    public function forAll(callable $f)
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
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function head()
    {
        foreach ($this->values as $x) {
            return $x;
        }
        throw new RuntimeException('There is no values.');
    }

    /**
     * {@inheritdoc}
     */
    public function headOption()
    {
        foreach ($this->values as $x) {
            return Option::some($x);
        }
        return Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
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
    public function lastOption()
    {
        return $this->takeRight(1)->headOption();
    }

    /**
     * {@inheritdoc}
     */
    public function nonEmpty()
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
        return $this->values[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $x)
    {
        throw new BadMethodCallException;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException;
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return count($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function take($n)
    {
        return Seq::fromArray(array_slice($this->toArray(), 0, $n));
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight($n)
    {
        return Seq::fromArray(array_slice($this->toArray(), 0 - $n));
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq()
    {
        return Seq::fromArray($this->values);
    }

}
