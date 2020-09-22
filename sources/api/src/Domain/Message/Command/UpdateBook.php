<?php

namespace Ddd\Domain\Message\Command;

use Ddd\Domain\Entity\Book;

class UpdateBook
{
    private Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function getBook(): Book
    {
        return $this->book;
    }
}
