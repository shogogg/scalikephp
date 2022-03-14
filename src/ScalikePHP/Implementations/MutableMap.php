<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Implementations;

/**
 * A Mutable Map Implementation.
 */
class MutableMap extends ArrayMap
{
    use ArraySupport;
    use MutableMapOps;

    /**
     * Constructor.
     *
     * The constructor of {@see \ScalikePHP\MutableMap}.
     *
     * @param iterable $iterable 値
     */
    public function __construct(iterable $iterable)
    {
        parent::__construct($iterable instanceof \Traversable ? iterator_to_array($iterable) : $iterable);
    }

    // overrides
    public function append($keyOrArray, $value = null): self
    {
        if (is_iterable($keyOrArray)) {
            foreach ($keyOrArray as $key => $value) {
                $this->array[$key] = $value;
            }
        } else {
            $this->array[$keyOrArray] = $value;
        }
        return $this;
    }

    // overrides
    public function filter(\Closure $p): self
    {
        return new self($this->filterGenerator($p));
    }

    // overrides
    public function flatMap(\Closure $f): self
    {
        return new self($this->flatMapGenerator($f));
    }

    // overrides
    public function map(\Closure $f): self
    {
        return new self($this->mapGenerator($f));
    }

    // overrides
    public function mapValues(\Closure $f): self
    {
        return new self($this->mapValuesGenerator($f));
    }
}
