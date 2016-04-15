<?php
namespace ScalikePHP;

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
     * 指定されたキーが存在するかどうかを判定する
     *
     * @param string $key
     * @return bool
     */
    public function contains($key)
    {
        return $this->values instanceof \ArrayAccess
            ? $this->values->offsetExists($key)
            : array_key_exists($key, $this->toArray());
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
