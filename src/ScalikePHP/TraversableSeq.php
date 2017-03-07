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
use ScalikePHP\Support\TraversableSupport;

/**
 * A Seq implementation using iterator(\Traversable).
 */
class TraversableSeq extends Seq
{

    use TraversableSupport;

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
        return new TraversableSeq($this->mergeGenerator($this->traversable, $that));
    }

    /**
     * @inheritdoc
     */
    public function contains($elem): bool
    {
        return in_array($elem, $this->toArray(), true);
    }

    /**
     * @inheritdoc
     */
    public function fold($z, \Closure $f)
    {
        foreach ($this->traversable as $value) {
            $z = $f($z, $value);
        }
        return $z;
    }

    /**
     * @inheritdoc
     */
    public function prepend(iterable $that): Seq
    {
        return new TraversableSeq($this->mergeGenerator($that, $this->traversable));
    }

    /**
     * @inheritdoc
     */
    public function toSeq(): Seq
    {
        return $this;
    }

}
