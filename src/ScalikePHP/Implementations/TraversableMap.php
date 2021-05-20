<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

use Generator;
use ScalikePHP\Map;
use ScalikePHP\Option;
use ScalikePHP\Seq;
use Traversable;

/**
 * A Map Implementation using \Traversable.
 */
class TraversableMap extends Map
{
    use MapOps;
    use TraversableSupport;

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\TraversableMap}.
     *
     * @param Traversable $traversable
     */
    public function __construct(Traversable $traversable)
    {
        $this->setTraversable($traversable);
    }

    // overrides
    public function append($keyOrArray, $value = null): Map
    {
        return Map::create(function () use ($keyOrArray, $value): Generator {
            yield from $this->traversable;
            yield from is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value];
        });
    }

    // overrides
    public function contains($key): bool
    {
        return array_key_exists($key, $this->toAssoc());
    }

    // overrides
    public function drop(int $n): Map
    {
        return $n <= 0 ? $this : Map::create(function () use ($n): Traversable {
            $i = $n;
            foreach ($this->getRawIterable() as $key => $value) {
                if ($i <= 0) {
                    yield $key => $value;
                } else {
                    --$i;
                }
            }
        });
    }

    // overrides
    public function get($key): Option
    {
        return Option::fromArray($this->toAssoc(), $key);
    }

    // overrides
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->toAssoc()));
    }

    // overrides
    public function take(int $n): Map
    {
        if ($n > 0) {
            return Map::create(function () use ($n): Traversable {
                $i = $n;
                foreach ($this->getRawIterable() as $key => $value) {
                    yield $key => $value;
                    if (--$i <= 0) {
                        break;
                    }
                }
            });
        } elseif ($n === 0) {
            return Map::empty();
        } else {
            return $this;
        }
    }

    // overrides
    public function toAssoc(): array
    {
        $this->compute();
        return $this->array;
    }

    // overrides
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->toAssoc()));
    }

    // overrides
    protected function compute(): void
    {
        if ($this->computed === false) {
            $this->array = iterator_to_array($this->traversable, true);
            $this->computed = true;
        }
    }
}
