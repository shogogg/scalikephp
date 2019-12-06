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
     * @param array $values
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
        return new TraversableSeq(function () use ($that): \Generator {
            yield from $this->array;
            yield from $that;
        });
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return new TraversableSeq(function () use ($that): \Generator {
            yield from $that;
            yield from $this->array;
        });
    }

    /**
     * @inheritdoc
     * @return Seq
     */
    public function take(int $n): Seq
    {
        return new ArraySeq(array_slice($this->array, 0, $n));
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return $this;
    }

}
