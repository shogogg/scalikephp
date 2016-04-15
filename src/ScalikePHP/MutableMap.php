<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * A Mutable Map Implementation
 */
class MutableMap extends ArrayMap
{

    /**
     * Constructor
     *
     * @param array|\Traversable $values 値
     */
    public function __construct($values)
    {
        if (is_array($values)) {
            $this->values = $values;
        } elseif ($values instanceof PhpTraversable) {
            $this->values = [];
            foreach ($values as $key => $x) {
                $this->values[$key] = $x;
            }
        } else {
            throw new \InvalidArgumentException('MutableMap needs an array or \Traversable.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function append($keyOrArray, $value = null)
    {
        if (is_array($keyOrArray)) {
            $this->values = $keyOrArray + $this->toArray();
        } elseif ($keyOrArray instanceof Map) {
            $this->values = $keyOrArray->toArray() + $this->toArray();
        } elseif ($keyOrArray instanceof \Traversable) {
            $this->values = Map::from($keyOrArray)->toArray() + $this->toArray();
        } else {
            $this->values[$keyOrArray] = $value;
        }
        return $this;
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
        return Map::mutable($array);
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
        return Map::from($array);
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
     * 要素を取得する, 要素が存在しない場合は $op の値で更新し、その値を返す
     *
     * $op が callable の場合はその実行結果を用いる
     *
     * @param string $key
     * @param mixed $op
     * @return mixed
     */
    public function getOrElseUpdate($key, $op)
    {
        return $this->get($key)->getOrCall(function () use ($key, $op) {
            $value = is_callable($op) ? call_user_func($op) : $op;
            $this->update($key, $value);
            return $value;
        });
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
        return Map::mutable($array);
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
        return Map::mutable($array);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $x)
    {
        $this->update($offset, $x);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     * 指定したキーに該当する要素を削除し、その値を返す
     *
     * @param string $key
     * @return Option 該当する要素がある場合に Some, ない場合は None
     */
    public function remove($key)
    {
        if (isset($this->values[$key])) {
            $value = $this->values[$key];
            unset($this->values[$key]);
            return Option::some($value);
        } else {
            return Option::none();
        }
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
     * 新しい値を追加する
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function update($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return Seq::fromArray($this->values);
    }

}
