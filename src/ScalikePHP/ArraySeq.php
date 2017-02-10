<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * A Seq implementation using array.
 */
class ArraySeq extends IterableSeq
{

    /**
     * Constructor.
     *
     * @param mixed[] $values
     */
    public function __construct(array $values)
    {
        $this->array = array_values($values);
        parent::__construct($this->array);
    }

    /**
     * @inheritdoc
     */
    public function append(iterable $that): Seq
    {
        return is_array($that)
            ? Seq::fromArray(array_merge($this->array, $that))
            : Seq::fromArray($this->mergeGenerator($this->values, $that));
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return is_array($that)
            ? Seq::fromArray(array_merge($that, $this->values))
            : Seq::fromArray($this->mergeGenerator($that, $this->values));
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->array;
    }

}
