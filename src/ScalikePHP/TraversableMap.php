<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * A Map Implementation using \Traversable
 */
class TraversableMap extends ArrayMap
{

    /**
     * Arrayed values
     *
     * @var array
     */
    private $array = null;

    /**
     * Constructor
     *
     * @param PhpTraversable $values 値
     */
    public function __construct(PhpTraversable $values)
    {
        $this->values = $values;
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
    public function toArray()
    {
        if ($this->array === null) {
            $this->array = [];
            foreach ($this->values as $key => $x) {
                $this->array[$key] = $x;
            }
        }
        return $this->array;
    }

}
