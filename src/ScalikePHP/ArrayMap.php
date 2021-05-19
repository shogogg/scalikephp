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

/**
 * A Seq implementation using array.
 */
class ArrayMap extends Map
{
    use ArraySupport;

    /**
     * Constructor.
     *
     * @param array $assoc
     */
    public function __construct(array $assoc)
    {
        $this->setArray($assoc);
    }

    /** {@inheritdoc} */
    public function append($keyOrArray, $value = null)
    {
        $assoc = array_merge(
            $this->array,
            is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value]
        );
        return new self($assoc);
    }

    /** {@inheritdoc} */
    public function contains($key): bool
    {
        return isset($this->array[$key]);
    }

    /** {@inheritdoc} */
    public function drop(int $n): Map
    {
        return $n <= 0 ? $this : new self(array_slice($this->array, $n));
    }

    /** {@inheritdoc} */
    public function get($key): Option
    {
        return Option::fromArray($this->array, $key);
    }

    /** {@inheritdoc} */
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->array));
    }

    /** {@inheritdoc} */
    public function take(int $n): Map
    {
        if ($n > 0) {
            return new self(array_slice($this->array, 0, $n));
        } elseif ($n === 0) {
            return Map::emptyMap();
        } else {
            return $this;
        }
    }

    /** {@inheritdoc} */
    public function toAssoc(): array
    {
        return $this->array;
    }

    /** {@inheritdoc} */
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->array));
    }
}
