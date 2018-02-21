<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

abstract class DateTimeValueObject
{
    private $value;

    public function __construct(\DateTimeImmutable $value)
    {
        $this->value = $value;
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function equals(DateTimeValueObject $other): bool
    {
        return get_class($other) === static::class && $other->value == $this->value;
    }
}