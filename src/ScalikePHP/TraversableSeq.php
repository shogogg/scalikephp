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
     * @inheritdoc
     */
    public function contains($elem): bool
    {
        foreach ($this->values as $x) {
            if ($x === $elem) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function each(\Closure $f): void
    {
        foreach ($this->values as $x) {
            call_user_func($f, $x);
        }
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): \Iterator
    {
        return $this->values instanceof \IteratorAggregate
            ? $this->values->getIterator()
            : new \ArrayIterator($this->toArray());
    }

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return $this->values instanceof \JsonSerializable ? $this->values->jsonSerialize() : $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $f): Seq
    {
        $array = [];
        foreach ($this->values as $x) {
            $array[] = call_user_func($f, $x);
        }
        return new ArraySeq($array);
    }

    /**
     * @inheritdoc
     */
    public function size(): int
    {
        return $this->values instanceof \Countable ? count($this->values) : count($this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
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
