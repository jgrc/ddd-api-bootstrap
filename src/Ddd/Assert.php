<?php
namespace Jgrc\Bootstrap\Ddd;

use Assert\Assert as BeberleiAssert;
use Jgrc\Bootstrap\Ddd\Exception\AssertException;

class Assert extends BeberleiAssert
{
    protected static $lazyAssertionExceptionClass = AssertException::class;
}