<?php
namespace Jgrc\Bootstrap\Ddd\Exception;

abstract class NotFoundException extends \Exception
{
    private $resource;
    private $id;

    public function __construct(string $resource, string $id)
    {
        $this->resource = $resource;
        $this->id = $id;

        parent::__construct(sprintf(
            'Resource %s with id %s not found',
            $this->resource,
            $this->id
        ));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function resource(): string
    {
        return $this->resource;
    }
}