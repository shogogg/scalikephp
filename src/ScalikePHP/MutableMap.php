<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\ArraySupport;

/**
 * A Mutable Map Implementation.
 */
class MutableMap extends ArrayMap
{

    use ArraySupport;

    /**
     * Constructor.
     *
     * @param iterable $iterable 値
     */
    public function __construct(iterable $iterable)
    {
        if (is_array($iterable)) {
            parent::__construct($iterable);
        } else {
            $assoc = [];
            foreach ($iterable as $key => $value) {
                $assoc[$key] = $value;
            }
            parent::__construct($assoc);
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
                $this->array[$key] = $value;
            }
        } else {
            $this->array[$keyOrArray] = $value;
        }
        return $this;
    }

    /**
     * @inheritdoc
     * @return MutableMap
     */
    public function filter(\Closure $p): MutableMap
    {
        return new MutableMap($this->filterGenerator($this->array, $p));
    }

    /**
     * @inheritdoc
     * @return MutableMap
     */
    public function flatMap(\Closure $f): MutableMap
    {
        return new MutableMap($this->flatMapGenerator($this->array, $f));
    }

    /**
     * 要素を取得する, 要素が存在しない場合は $op の値で更新し、その値を返す.
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
        return new MutableMap($this->mapGenerator($this->array, $f));
    }

    /**
     * @inheritdoc
     */
    public function mapValues(\Closure $f): Map
    {
        return new MutableMap($this->mapValuesGenerator($this->array, $f));
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->update($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->array[$offset]);
    }

    /**
     * 指定したキーに該当する要素を削除し、その値を返す.
     *
     * @param string $key
     * @return Option 該当する要素がある場合に Some, ない場合は None
     */
    public function remove($key): Option
    {
        if (isset($this->array[$key])) {
            $value = $this->array[$key];
            unset($this->array[$key]);
            return Option::some($value);
        } else {
            return Option::none();
        }
    }

    /**
     * 新しい値を追加する.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function update($key, $value): void
    {
        $this->array[$key] = $value;
    }

}
