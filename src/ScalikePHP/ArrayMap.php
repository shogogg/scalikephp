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
        } elseif ($keyOrArray instanceof Map) {
            return new ArrayMap($keyOrArray->toArray() + $this->toArray());
        } elseif ($keyOrArray instanceof \Traversable) {
            return new ArrayMap(Map::from($keyOrArray)->toArray() + $this->toArray());
        } else {
            return new ArrayMap([$keyOrArray => $value] + $this->toArray());
        }
    }

    /**
     * 指定されたキーが存在するかどうかを判定する
     *
     * @param string $key
     * @return bool
     */
    public function contains($key)
    {
        return array_key_exists($key, $this->values);
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
            } elseif ($result instanceof ScalikeTraversable) {
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
    public function get($key)
    {
        return Option::fromArray($this->values, $key);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse($key, $default)
    {
        return $this->get($key)->getOrElse($default);
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
