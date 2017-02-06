<?php
namespace ScalikePHP;

/**
 * Scala like None.
 */
final class None extends Option
{

    /**
     * Singleton instance
     *
     * @var None
     */
    private static $instance = null;

    /**
     * Get a None instance
     *
     * @return None
     */
    public static function getInstance(): None
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct([]);
    }

    /**
     * @inheritdoc
     */
    public function filter(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function flatMap(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function flatten(): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        throw new \LogicException("None has no value.");
    }

    /**
     * @inheritdoc
     */
    public function getOrCall(\Closure $f)
    {
        return $this->getOrElse($f);
    }

    /**
     * @inheritdoc
     */
    public function getOrElse(\Closure $default)
    {
        return $default();
    }

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    public function getOrThrow(\Exception $exception)
    {
        throw $exception;
    }

    /**
     * @inheritdoc
     */
    public function isDefined(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function map(\Closure $callback): None
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function max()
    {
        throw new \LogicException("empty.max");
    }

    /**
     * @inheritdoc
     */
    public function maxBy(\Closure $f)
    {
        throw new \LogicException("empty.max");
    }

    /**
     * @inheritdoc
     */
    public function min()
    {
        throw new \LogicException("empty.min");
    }

    /**
     * @inheritdoc
     */
    public function minBy(\Closure $f)
    {
        throw new \LogicException("empty.min");
    }

    /**
     * @inheritdoc
     */
    public function orElse(\Closure $b): Option
    {
        $x = $b();
        if ($x instanceof Option) {
            return $x;
        } else {
            throw new \LogicException("Closure should returns an Option");
        }
    }

    /**
     * @inheritdoc
     */
    public function orNull()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function orElseCall(\Closure $f): Option
    {
        return $f();
    }

    /**
     * @inheritdoc
     */
    public function pick($name): Option
    {
        return $this;
    }

}
