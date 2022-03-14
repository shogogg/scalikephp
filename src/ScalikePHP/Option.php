<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
declare(strict_types=1);

namespace ScalikePHP;

use ScalikePHP\Support\OptionBuilder;

/**
 * Scala like Option.
 */
abstract class Option extends ScalikeTraversable
{
    use OptionBuilder;

    /**
     * Returns the value, throw exception if empty.
     *
     * @throws \LogicException if this Option is empty.
     * @return mixed
     */
    abstract public function get();

    /**
     * Returns the value if the Option is non-empty, otherwise return the result of evaluating `$default`.
     *
     * @param \Closure $default the default expression.
     * @return mixed
     */
    abstract public function getOrElse(\Closure $default);

    /**
     * Returns the value if this Option is non-empty, otherwise return the `$default`.
     *
     * @param mixed $default the default value.
     * @return mixed
     */
    abstract public function getOrElseValue($default);

    /**
     * Returns true if this Option is non-empty, false otherwise.
     *
     * @return bool
     */
    abstract public function isDefined(): bool;

    /**
     * Returns this Option if it is non-empty, otherwise return the result of evaluating `$alternative`.
     *
     * @param \Closure $alternative
     * @return \ScalikePHP\Option
     */
    abstract public function orElse(\Closure $alternative): self;

    /**
     * Returns the value if this Option is non-empty, otherwise return `null`.
     *
     * @return null|mixed
     */
    abstract public function orNull();

    /**
     * Returns the value of a single column of this Option's value if it exists, otherwise return None.
     *
     * @param string $name the key of array, or property name.
     * @return \ScalikePHP\Option
     */
    abstract public function pick(string $name): self;
}
