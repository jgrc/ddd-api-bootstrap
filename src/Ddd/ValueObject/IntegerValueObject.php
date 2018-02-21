<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

abstract class IntegerValueObject
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(IntegerValueObject $other): bool
    {
        return get_class($other) === static::class && $other->value === $this->value;
    }
}