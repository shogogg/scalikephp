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
use ScalikePHP\Support\SeqOps;
use ScalikePHP\Support\TraversableSupport;
use Traversable;

/**
 * A Seq implementation using iterator(\Traversable).
 */
class TraversableSeq extends Seq
{
    use SeqOps;
    use TraversableSupport;

    /**
     * {@link \ScalikePHP\TraversableSeq} Constructor.
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
    public function computed(): Seq
    {
        return new ArraySeq($this->toArray());
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
    public function indexOf($elem): int
    {
        $index = array_search($elem, $this->toArray(), true);
        return $index === false ? -1 : $index;
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
            $this->array = [...$this->traversable];
            $this->computed = true;
        }
    }
}
