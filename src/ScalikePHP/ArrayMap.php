<?php
namespace ScalikePHP;

/**
 * A Map Implementation using PHP array
 */
class ArrayMap extends Map
{

    /**
     * Constructor
     *
     * @param array $values 値
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function append($keyOrArray, $value = null)
    {
        if (is_array($keyOrArray)) {
            return new ArrayMap($keyOrArray + $this->toArray());
        } elseif ($keyOrArray instanceof Map || method_exists($keyOrArray, 'toArray')) {
            return new ArrayMap($keyOrArray->toArray() + $this->toArray());
        } elseif ($keyOrArray instanceof \Traversable) {
            return new ArrayMap(Map::from($keyOrArray)->toArray() + $this->toArray());
        } else {
            return new ArrayMap([$keyOrArray => $value] + $this->toArray());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            if (call_user_func($f, $x, $key)) {
                $array[$key] = $x;
            }
        }
        return new ArrayMap($array);
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $result = call_user_func($f, $x, $key);
            if (is_array($result)) {
                $array = $result + $array;
            } elseif ($result instanceof ScalikeTraversable || method_exists($result, 'toArray')) {
                $array = $result->toArray() + $array;
            } elseif ($result instanceof \Traversable) {
                $array = Map::from($result)->toArray() + $array;
            } else {
                throw new \InvalidArgumentException('$f should returns a Traversable or an array.');
            }
        }
        return new ArrayMap($array);
    }

    /**
     * {@inheritdoc}
     */
    public function fold($z, callable $f)
    {
        foreach ($this->values as $key => $x) {
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
    public function keys()
    {
        return Seq::fromArray(array_keys($this->values));
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            list($newKey, $newValue) = call_user_func($f, $x, $key);
            $array[$newKey] = $newValue;
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     */
    public function mapValues(callable $f)
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $array[$key] = call_user_func($f, $x);
        }
        return Map::from($array);
    }

    /**
     * {@inheritdoc}
     */
    public function toSeq()
    {
        $array = [];
        foreach ($this->values as $key => $x) {
            $array[] = [$key, $x];
        }
        return Seq::fromArray($array);
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return Seq::fromArray($this->values);
    }

}
