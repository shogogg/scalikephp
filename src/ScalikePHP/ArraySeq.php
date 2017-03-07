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
use ScalikePHP\Support\SeqSupport;

/**
 * A Seq implementation using array.
 */
class ArraySeq extends Seq
{

    use ArraySupport, SeqSupport;

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
