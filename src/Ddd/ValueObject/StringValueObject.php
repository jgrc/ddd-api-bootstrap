<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

abstract class StringValueObject
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $other): bool
    {
        return get_class($other) === static::class && $other->value === $this->value;
    }
}