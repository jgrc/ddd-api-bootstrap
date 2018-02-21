<?php
namespace Jgrc\Bootstrap\Ddd\ValueObject;

class CollectionValueObject implements \Iterator, \Countable
{
    private $items;
    private $current;

    private function __construct(array $items = [])
    {
        $this->items = [];
        $this->current = 0;
        array_walk(
            $items,
            function ($item) {
                $this->add($item);
            }
        );
    }

    private function add($item): void
    {
        $this->assert($item);
        $this->items[] = $item;
    }

    private function assert($item): void
    {
        if (false === $this->availableItem($item)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid item %s in collection',
                    get_class($item)
                )
            );
        }
    }

    protected function availableItem($item): bool
    {
        return true;
    }

    public function current()
    {
        return $this->items[$this->current];
    }

    public function next()
    {
        $this->current += 1;

        return $this;
    }

    public function key(): int
    {
        return $this->current;
    }

    public function valid(): bool
    {
        return array_key_exists($this->current, $this->items);
    }

    public function rewind()
    {
        $this->current = 0;

        return $this;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function walk(callable $func): void
    {
        array_walk($this->items, $func);
    }

    public function filter(callable $func)
    {
        return new static(array_filter($this->items, $func));
    }

    public function map(callable $func)
    {
        return new self(array_map($func, $this->items));
    }

    public function reduce(callable $func, $initial)
    {
        return array_reduce($this->items, $func, $initial);
    }

    public static function from($items = null)
    {
        switch (true) {
            case null === $items:
                $items = [];
                break;
            case $items instanceof self:
                $items = $items->items;
                break;
            case is_array($items):
                break;
            default:
                $items = [$items];
        }

        return new static($items);
    }

    public function __toArray(): array
    {
        return $this->items;
    }
}