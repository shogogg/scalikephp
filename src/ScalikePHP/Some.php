<?php
namespace ScalikePHP;

/**
 * Scala like Some
 */
final class Some extends Option
{

    /**
     * Create a Some instance
     *
     * @param mixed $value 値
     * @return Some
     */
    public static function create($value)
    {
        return new static($value);
    }

    /**
     * Constructor
     *
     * @param mixed $value 値
     */
    private function __construct($value)
    {
        $this->values = [$value];
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $f)
    {
        return call_user_func($f, $this->values[0]) ? $this : Option::none();
    }

    /**
     * {@inheritdoc}
     */
    public function flatMap(callable $f)
    {
        return call_user_func($f, $this->values[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrCall(callable $f)
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrElse($default)
    {
        return $this->values[0];
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
     */
    public function getOrThrow(\Exception $exception)
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function isDefined()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $x = $this->values[0];
        return $x instanceof \JsonSerializable ? $x->jsonSerialize() : $x;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $f)
    {
        return static::create(call_user_func($f, $this->values[0]));
    }

    /**
     * {@inheritdoc}
     */
    public function max()
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function maxBy(callable $f)
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function min()
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function minBy(callable $f)
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(Option $b)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function orNull()
    {
        return $this->values[0];
    }

    /**
     * {@inheritdoc}
     */
    public function orElseCall(callable $f)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function pick($name)
    {
        $x = $this->values[0];
        if (is_array($x) || $x instanceof \ArrayAccess) {
            return Option::fromArray($x, $name);
        } elseif (is_object($x) && (property_exists($x, $name) || method_exists($x, '__get'))) {
            return Option::from($x->{$name});
        } else {
            return Option::none();
        }
    }

}
