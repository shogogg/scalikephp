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
 * A Map Implementation using \Traversable.
 */
class TraversableMap extends Map
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
    public function append($keyOrArray, $value = null): Map
    {
        return Map::create(function () use ($keyOrArray, $value): Generator {
            yield from $this->traversable;
            yield from is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value];
        });
    }

    /**
     * {@inheritdoc}
     */
    public function contains($key): bool
    {
        return array_key_exists($key, $this->toAssoc());
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function get($key): Option
    {
        return Option::fromArray($this->toAssoc(), $key);
    }

    /**
     * {@inheritdoc}
     */
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->toAssoc()));
    }

    /**
     * {@inheritdoc}
     */
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
            return Map::emptyMap();
        } else {
            return $this;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toAssoc(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * {@inheritdoc}
     */
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->toAssoc()));
    }

    /**
     * {@inheritdoc}
     */
    protected function compute(): void
    {
        if ($this->computed === false) {
            $this->array = iterator_to_array($this->traversable, true);
            $this->computed = true;
        }
    }
}
