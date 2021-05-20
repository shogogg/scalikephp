<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use ScalikePHP\Support\ArraySupport;
use ScalikePHP\Support\SeqOps;

/**
 * A Seq implementation using array.
 */
class ArraySeq extends Seq
{
    use ArraySupport;
    use SeqOps;

    /**
     * {@link \ScalikePHP\ArraySeq} Constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->setArray(array_values($values));
    }

    /**
     * {@inheritdoc}
     */
    public function computed(): Seq
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function drop(int $n): Seq
    {
        return $n <= 0 ? $this : new self(array_slice($this->array, $n));
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($elem): int
    {
        $index = array_search($elem, $this->array, true);
        return $index === false ? -1 : $index;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return $this->array;
    }
}
