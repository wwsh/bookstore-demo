<?php

namespace Ddd\Domain\Message\Command;

class DeleteBook
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
