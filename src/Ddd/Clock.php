<?php
namespace Jgrc\Bootstrap\Ddd;

class Clock
{
    private static $fakeNow;

    public static function fakeNow(\DateTimeImmutable $datetime): void
    {
        self::$fakeNow = $datetime;
    }

    public static function now(): \DateTimeImmutable
    {
        return self::$fakeNow ? self::$fakeNow : new \DateTimeImmutable();
    }

    public static function from(string $str): \DateTimeImmutable
    {
        return new \DateTimeImmutable($str);
    }
}