<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types = 1);

namespace ScalikePHP\Support;

use ScalikePHP\Option;
use ScalikePHP\ScalikeTraversable;

/**
 * ScalikeTraversable support functions.
 */
trait GeneralSupport
{

    /**
     * Generate a Closure for `groupBy`.
     *
     * @param string|\Closure $f
     * @return \Closure
     */
    protected function groupByClosure($f): \Closure
    {
        if (is_string($f)) {
            return function ($value) use ($f) {
                return Option::from($value)->pick($f)->getOrElse(function () use ($f): void {
                    throw new \RuntimeException("Undefined index {$f}");
                });
            };
        } elseif ($f instanceof \Closure) {
            return $f;
        } else {
            $type = gettype($f);
            throw new \InvalidArgumentException("`groupBy` needs a string or \\Closure. {$type} given.");
        }
    }

    /**
     * Create new element for `groupBy`.
     *
     * @param mixed $value
     * @param mixed $key
     * @return ScalikeTraversable
     */
    abstract protected function groupByElement($value, $key): ScalikeTraversable;

}
