<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

use ScalikePHP\Seq;

/**
 * A Seq implementation using iterator(\Traversable).
 */
class TraversableSeq extends Seq
{
    use SeqOps;
    use TraversableSupport;

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\TraversableSeq}.
     *
     * @param \Traversable $traversable
     */
    public function __construct(\Traversable $traversable)
    {
        $this->setTraversable($traversable);
    }

    // overrides
    public function computed(): Seq
    {
        return new ArraySeq($this->toArray());
    }

    // overrides
    public function drop(int $n): Seq
    {
        return $n <= 0 ? $this : Seq::create(function () use ($n): \Generator {
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

    // overrides
    public function indexOf($elem): int
    {
        $index = array_search($elem, $this->toArray(), true);
        return $index === false ? -1 : $index;
    }

    // overrides
    public function take(int $n): Seq
    {
        if ($n > 0) {
            return Seq::create(function () use ($n): \Generator {
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

    // overrides
    public function toArray(): array
    {
        $this->compute();
        return $this->array;
    }

    // overrides
    protected function compute(): void
    {
        if ($this->computed === false) {
            $this->array = [...$this->traversable];
            $this->computed = true;
        }
    }
}
