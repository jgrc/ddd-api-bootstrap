<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

abstract class DecimalValueObject
{
    private $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equals(DecimalValueObject $other): bool
    {
        return get_class($other) === static::class && $other->value === $this->value;
    }
}