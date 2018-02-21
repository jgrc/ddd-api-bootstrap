<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

use Jgrc\Bootstrap\Ddd\Assert;
use Jgrc\Bootstrap\Ddd\Uuid;

class IdentifierValueObject extends StringValueObject
{
    public function __construct(string $id)
    {
        Assert::lazy()
            ->that($id, 'id')
            ->uuid('The id should be a UUID')
            ->verifyNow();

        parent::__construct($id);
    }


    public static function create(): self
    {
        return new self(
            Uuid::generate()
        );
    }
}