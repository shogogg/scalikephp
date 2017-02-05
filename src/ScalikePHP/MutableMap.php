<?php
namespace ScalikePHP;

use Traversable as PhpTraversable;

/**
 * A Mutable Map Implementation
 */
class MutableMap extends IterableMap
{

    /**
     * Constructor.
     *
     * @param iterable $values 値
     */
    public function __construct(iterable $values)
    {
        if (is_array($values)) {
            parent::__construct($values);
        } elseif ($values instanceof PhpTraversable) {
            parent::__construct([]);
            foreach ($values as $key => $value) {
                $this->values[$key] = $value;
            }
        } else {
            throw new \InvalidArgumentException("MutableMap needs an iterable");
        }
    }

    /**
     * @inheritdoc
     * @return MutableMap
     */
    public function append($keyOrArray, $value = null): MutableMap
    {
        if (is_iterable($keyOrArray)) {
            foreach ($keyOrArray as $key => $value) {
                $this->values[$key] = $value;
            }
        } else {
            $this->values[$keyOrArray] = $value;
        }
        return $this;
    }

    /**
     * @inheritdoc
     * @return MutableMap
     */
    public function filter(\Closure $p): MutableMap
    {
        return Map::mutable($this->filterGenerator($this->values, $p));
    }

    /**
     * @inheritdoc
     * @return MutableMap
     */
    public function flatMap(\Closure $f): MutableMap
    {
        return Map::mutable($this->flatMapGenerator($this->values, $f));
    }

    /**
     * 要素を取得する, 要素が存在しない場合は $op の値で更新し、その値を返す
     *
     * $op が \Closure の場合はその実行結果を用いる
     *
     * @param mixed $key
     * @param \Closure $op
     * @return mixed
     */
    public function getOrElseUpdate($key, \Closure $op)
    {
        return $this->get($key)->getOrElse(function () use ($key, $op) {
            $value = $op();
            $this->update($key, $value);
            return $value;
        });
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $f): Map
    {
        return Map::mutable($this->mapGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     */
    public function mapValues(\Closure $f): Map
    {
        return Map::mutable($this->mapValuesGenerator($this->values, $f));
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $x)
    {
        $this->update($offset, $x);
    }

    /**
     * @inheritdoc
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
    public function remove($key): Option
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
     * 新しい値を追加する
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function update($key, $value): void
    {
        $this->values[$key] = $value;
    }

}
