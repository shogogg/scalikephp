<?php
namespace ScalikePHP;

/**
 * A Seq Implementation using \Traversable
 */
class TraversableSeq extends ArraySeq
{

    /**
     * 値
     * @var \Traversable
     */
    protected $values;

    /**
     * Constructor
     *
     * @param \Traversable $values 値
     */
    public function __construct(\Traversable $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function each(callable $f)
    {
        foreach ($this->values as $x) {
            call_user_func($f, $x);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->values instanceof \IteratorAggregate
            ? $this->values->getIterator()
            : new \ArrayIterator($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->values instanceof \JsonSerializable ? $this->values->jsonSerialize() : $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f)
    {
        $array = [];
        foreach ($this->values as $x) {
            $array[] = call_user_func($f, $x);
        }
        return new ArraySeq($array);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return $this->values instanceof \Countable ? count($this->values) : count($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        static $array = null;
        if ($array === null) {
            $array = [];
            foreach ($this->values as $x) {
                $array[] = $x;
            }
        }
        return $array;
    }

}
