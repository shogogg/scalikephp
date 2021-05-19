<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use Generator;
use ScalikePHP\Support\TraversableSupport;
use Traversable;

/**
 * A Seq implementation using iterator(\Traversable).
 */
class TraversableSeq extends Seq
{
    use TraversableSupport;

    /**
     * Constructor.
     *
     * @param Traversable $traversable
     */
    public function __construct(Traversable $traversable)
    {
        $this->setTraversable($traversable);
    }

    /**
     * {@inheritdoc}
     */
    public function drop(int $n): Seq
    {
        return $n <= 0 ? $this : Seq::create(function () use ($n): Generator {
            $i = $n;
            $index = 0;
            foreach ($this->getRawIterable() as $value) {
                if ($i <= 0) {
                    yield $index++ => $value;
                } else {
                    --$i;
                }
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function take(int $n): Seq
    {
        if ($n > 0) {
            return Seq::create(function () use ($n): Generator {
                $i = $n;
                $index = 0;
                foreach ($this->getRawIterable() as $value) {
                    yield $index++ => $value;
                    if (--$i <= 0) {
                        break;
                    }
                }
            });
        } elseif ($n === 0) {
            return Seq::empty();
        } else {
            return $this;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * {@inheritdoc}
     */
    protected function compute(): void
    {
        if ($this->computed === false) {
            $this->array = iterator_to_array($this->traversable, false);
            $this->computed = true;
        }
    }
}
