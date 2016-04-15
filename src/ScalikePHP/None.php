<?php
namespace ScalikePHP;

/**
 * Scala like None
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
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->values = [];
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        throw new \RuntimeException("None has no value.");
    }

    /**
     * {@inheritdoc}
     */
    public function getOrCall(callable $callback)
    {
        return call_user_func($callback);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse($default)
    {
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrNull()
    {
        return $this->orNull();
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getOrThrow(\Exception $exception)
    {
        throw $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function isDefined()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        throw new \RuntimeException("empty.max");
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(callable $f)
    {
        throw new \RuntimeException("empty.max");
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        throw new \RuntimeException("empty.min");
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(callable $f)
    {
        throw new \RuntimeException("empty.min");
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(Option $b)
    {
        return $b;
    }

    /**
     * {@inheritdoc}
     */
    public function orNull()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function orElseCall(callable $f)
    {
        return $f();
    }

    /**
     * {@inheritdoc}
     */
    public function pick($name)
    {
        return $this;
    }

}
