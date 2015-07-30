<?php
namespace ScalikePHP;

/**
 * A Seq Implementation using \Traversable
 */
class TraversableSeq extends ArraySeq
{

    /**
     * Arrayed values
     *
     * @var array
     */
    private $array = null;

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
    public function contains($elem)
    {
        foreach ($this->values as $x) {
            if ($x === $elem) {
                return true;
            }
        }
        return false;
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
        if ($this->array === null) {
            $this->array = [];
            foreach ($this->values as $x) {
                $this->array[] = $x;
            }
        }
        return $this->array;
    }

}
