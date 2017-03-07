<?php
/**
 * Copyright (c) 2017 shogogg <shogo@studiofly.net>
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
namespace ScalikePHP;

/**
 * Scala like Traversable Implementation.
 */
abstract class ScalikeTraversable implements ScalikeTraversableInterface
{

    /**
     * @inheritdoc
     */
    public function filterNot(\Closure $p)
    {
        return $this->filter(function ($value) use ($p) {
            return !$p($value);
        });
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        return $this->takeRight(1)->toSeq()->head();
    }

    /**
     * @inheritdoc
     */
    public function lastOption(): Option
    {
        return $this->takeRight(1)->toSeq()->headOption();
    }

    /**
     * @inheritdoc
     */
    public function mkString(string $sep = ""): string
    {
        return implode($sep, $this->toArray());
    }

    /**
     * @inheritdoc
     */
    public function nonEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException;
    }

}
