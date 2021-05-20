<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;

/**
 * A Seq implementation using array.
 */
class ArrayMap extends Map
{
    use ArraySupport;
    use MapOps;

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\ArrayMap}.
     *
     * @param array $assoc
     */
    public function __construct(array $assoc)
    {
        $this->setArray($assoc);
    }

    // overrides
    public function append($keyOrArray, $value = null): Map
    {
        $assoc = array_merge(
            $this->array,
            $this->array,
            is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value]
        );
        return new self($assoc);
    }

    // overrides
    public function contains($key): bool
    {
        return isset($this->array[$key]);
    }

    // overrides
    public function drop(int $n): Map
    {
        return $n <= 0 ? $this : new self(array_slice($this->array, $n));
    }

    // overrides
    public function get($key): Option
    {
        return Option::fromArray($this->array, $key);
    }

    // overrides
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->array));
    }

    // overrides
    public function take(int $n): Map
    {
        if ($n > 0) {
            return new self(array_slice($this->array, 0, $n));
        } elseif ($n === 0) {
            return Map::empty();
        } else {
            return $this;
        }
    }

    // overrides
    public function toAssoc(): array
    {
        return $this->array;
    }

    // overrides
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->array));
    }
}
