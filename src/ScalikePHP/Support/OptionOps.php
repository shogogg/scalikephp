<?php
/**
 * Copyright (c) 2021 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP\Support;

use Generator;
use ScalikePHP\Option;

/**
 * Option operations.
 *
 * @mixin \ScalikePHP\Option
 */
trait OptionOps
{
    /**
     * {@inheritdoc}
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function take(int $n): Option
    {
        return $n <= 0 ? self::none() : $this;
    }

    /**
     * {@inheritdoc}
     * @noinspection PhpIncompatibleReturnTypeInspection
     */
    public function takeRight(int $n): Option
    {
        return $n <= 0 ? self::none() : $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toGenerator(): Generator
    {
        foreach ($this->toArray() as $index => $value) {
            yield $index => $value;
        }
    }
}
