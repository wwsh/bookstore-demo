<?php

namespace Ddd\Domain\MessageHandler\Query;

use Ddd\Domain\Message\Query\GetBooks;
use Ddd\Infrastructure\Persistence\BookRepository;

class GetBooksHandler
{
    private BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetBooks $getBooks): array
    {
        return $this->repository->findAll($getBooks->getOffset(), $getBooks->getLimit());
    }
}
