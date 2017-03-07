<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP;

use ScalikePHP\Support\MapSupport;
use ScalikePHP\Support\TraversableSupport;

/**
 * A Map Implementation using \Traversable.
 */
class TraversableMap extends Map
{

    use MapSupport, TraversableSupport {
        MapSupport::toArray insteadof TraversableSupport;
        MapSupport::toSeq insteadof TraversableSupport;
    }

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
    public function append($keyOrArray, $value = null)
    {
        $g = $this->mergeGenerator(
            $this->traversable,
            is_array($keyOrArray) ? $keyOrArray : [$keyOrArray => $value]
        );
        return new TraversableMap($g, true);
    }

    /**
     * @inheritdoc
     */
    public function contains($key): bool
    {
        return array_key_exists($key, $this->toAssoc());
    }

    /**
     * @inheritdoc
     */
    public function get($key): Option
    {
        return Option::fromArray($this->toAssoc(), $key);
    }

    /**
     * @inheritdoc
     */
    public function keys(): Seq
    {
        return new ArraySeq(array_keys($this->toAssoc()));
    }

    /**
     * @inheritdoc
     */
    public function toAssoc(): array
    {
        $this->compute();
        return $this->array;
    }

    /**
     * @inheritdoc
     */
    public function values(): Seq
    {
        return new ArraySeq(array_values($this->toAssoc()));
    }

}
