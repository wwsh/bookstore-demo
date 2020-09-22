<?php

namespace Ddd\Domain\Repository;

use Ddd\Domain\Entity\Book;

interface BookRepository
{
    public function findAll(int $offset, int $limit);

    public function find($id): ?Book;

    public function persist(Book $book): void;

    public function delete(int $id): void;
}
