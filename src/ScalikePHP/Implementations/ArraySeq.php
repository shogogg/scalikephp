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
 * A Seq implementation using array.
 */
class ArraySeq extends Seq
{
    use ArraySupport;
    use SeqOps;

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\ArraySeq}.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->setArray(array_values($values));
    }

    // overrides
    public function computed(): Seq
    {
        return $this;
    }

    // overrides
    public function drop(int $n): Seq
    {
        return $n <= 0 ? $this : new self(array_slice($this->array, $n));
    }

    // overrides
    public function indexOf($elem): int
    {
        $index = array_search($elem, $this->array, true);
        return $index === false ? -1 : $index;
    }

    // overrides
    public function take(int $n): Seq
    {
        if ($n > 0) {
            return new self(array_slice($this->array, 0, $n));
        } elseif ($n === 0) {
            return Seq::empty();
        } else {
            return $this;
        }
    }

    // overrides
    public function toArray(): array
    {
        return $this->array;
    }
}
