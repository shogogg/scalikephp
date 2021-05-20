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
use ScalikePHP\Option;

/**
 * Option operations.
 *
 * @mixin \ScalikePHP\Option
 */
trait OptionOps
{
    // overrides
    public function take(int $n): Option
    {
        return $n <= 0 ? self::none() : $this;
    }

    // overrides
    public function takeRight(int $n): Option
    {
        return $n <= 0 ? self::none() : $this;
    }

    // overrides
    public function toGenerator(): Generator
    {
        foreach ($this->toArray() as $index => $value) {
            yield $index => $value;
        }
    }
}
