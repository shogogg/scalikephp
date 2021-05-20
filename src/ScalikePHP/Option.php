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
use Generator;
use LogicException;
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
     * @throws LogicException if this option is empty.
     * @return mixed
     */
    abstract public function get();

    /**
     * Returns the value if the option is non-empty, otherwise return the result of evaluating `$default`.
     *
     * @param Closure $default the default expression.
     * @return mixed
     */
    abstract public function getOrElse(Closure $default);

    /**
     * Returns the value if the option is non-empty, otherwise return the `$default`.
     *
     * @param mixed $default the default value.
     * @return mixed
     */
    abstract public function getOrElseValue($default);

    /**
     * Returns true if the option is an instance of {@link \ScalikePHP\Some}, false otherwise.
     *
     * @return bool
     */
    abstract public function isDefined(): bool;

    /**
     * Returns this option if it is non-empty, otherwise return the result of evaluating `$alternative`.
     *
     * @param Closure $alternative
     * @return \ScalikePHP\Option
     */
    abstract public function orElse(Closure $alternative): self;

    /**
     * Returns the value if the option is non-empty, otherwise return `null`.
     *
     * @return null|mixed
     */
    abstract public function orNull();

    /**
     * Returns the value from a single column of this option's value if it exists, otherwise return {@link \ScalikePHP\None}.
     *
     * @param string $name the key of array, or property name.
     * @return \ScalikePHP\Option
     */
    abstract public function pick(string $name): self;

    /**
     * {@inheritdoc}
     */
    public function take(int $n): self
    {
        return $n <= 0 ? self::none() : $this;
    }

    /**
     * {@inheritdoc}
     */
    public function takeRight(int $n): self
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
