<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * A Map Implementation using \Traversable
 */
class TraversableMap extends ArrayMap
{

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
        static $array = null;
        if ($array === null) {
            foreach ($this->values as $key => $x) {
                $array[$key] = $x;
            }
        }
        return $array;
    }

}
