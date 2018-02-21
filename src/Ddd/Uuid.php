<?php
namespace Jgrc\Bootstrap\Ddd;

use \Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    private static $fakeUuid;

    public static function fake(string $uuid): void
    {
        self::$fakeUuid = $uuid;
    }

    public static function generate(): string
    {
        return self::$fakeUuid ? self::$fakeUuid : RamseyUuid::uuid4()->toString();
    }

    public static function strToBin(string $str): string
    {
        return RamseyUuid::fromString($str)->getBytes();
    }

    public static function binToStr(string $bin): string
    {
        return RamseyUuid::fromBytes($bin)->toString();
    }
}