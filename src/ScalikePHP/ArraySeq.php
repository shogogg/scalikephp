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
 * A Seq implementation using array.
 */
class ArraySeq extends Seq
{

    use ArraySupport;

    /**
     * Constructor.
     *
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->setArray(array_values($values));
    }

    /**
     * @inheritdoc
     */
    public function append(iterable $that): Seq
    {
        return is_array($that)
            ? new ArraySeq(array_merge($this->array, array_values($that)))
            : new TraversableSeq($this->mergeGenerator($this->array, $that));
    }

    /**
     * @inheritdoc
     */
    public function contains($elem): bool
    {
        return in_array($elem, $this->array, true);
    }

    /**
     * @inheritdoc
     */
    public function distinct(): Seq
    {
        // `array_keys(array_count_values(...))` is faster than `array_unique(...)`
        return new ArraySeq(array_keys(array_count_values($this->array)));
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->array as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return is_array($that)
            ? new ArraySeq(array_merge($that, $this->array))
            : new TraversableSeq($this->mergeGenerator($that, $this->array));
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return $this;
    }

}
