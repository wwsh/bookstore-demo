<?php

namespace Ddd\Domain\Message\Event;

use Ddd\Domain\Entity\Book;

class BookUpdated
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
