<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\SeqSupport;
use ScalikePHP\Support\TraversableSupport;

/**
 * A Seq implementation using iterator(\Traversable).
 */
class TraversableSeq extends Seq
{

    use SeqSupport, TraversableSupport;

    /**
     * Constructor.
     *
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->setClosure($closure);
    }

    /**
     * @inheritdoc
     */
    public function append(iterable $that): Seq
    {
        return new TraversableSeq(function () use ($that): \Generator {
            yield from $this->getRawIterable();
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
            yield from $this->getRawIterable();
        });
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return $this;
    }

    /** {@inheritdoc} */
    protected function compute(): void
    {
        if ($this->computed === false) {
            $this->array = iterator_to_array($this->getIterator(), false);
            $this->computed = true;
        }
    }

}
