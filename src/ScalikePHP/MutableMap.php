<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use Closure;
use ScalikePHP\Support\ArraySupport;
use ScalikePHP\Support\MutableMapOps;
use Traversable;

/**
 * A Mutable Map Implementation.
 */
class MutableMap extends ArrayMap
{
    use ArraySupport;
    use MutableMapOps;

    /**
     * {@link \ScalikePHP\MutableMap} Constructor.
     *
     * @param iterable $iterable 値
     */
    public function __construct(iterable $iterable)
    {
        parent::__construct($iterable instanceof Traversable ? iterator_to_array($iterable) : $iterable);
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
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

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function filter(Closure $p): self
    {
        return new self($this->filterGenerator($p));
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function flatMap(Closure $f): self
    {
        return new self($this->flatMapGenerator($f));
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function map(Closure $f): self
    {
        return new self($this->mapGenerator($f));
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function mapValues(Closure $f): self
    {
        return new self($this->mapValuesGenerator($f));
    }
}
