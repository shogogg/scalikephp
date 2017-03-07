<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\GeneratorIterator;
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
     * @param \Traversable $traversable
     */
    public function __construct(\Traversable $traversable)
    {
        $this->setTraversable($traversable);
    }

    /**
     * @inheritdoc
     */
    public function append(iterable $that): Seq
    {
        return new TraversableSeq($this->mergeGenerator($this->getRawIterable(), $that));
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return new TraversableSeq($this->mergeGenerator($that, $this->getRawIterable()));
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return $this;
    }

}
