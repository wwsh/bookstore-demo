<?php

namespace Ddd\Domain\MessageHandler\Query;

use Ddd\Domain\Entity\Book;
use Ddd\Domain\Message\Query\GetBook;
use Ddd\Infrastructure\Persistence\BookRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetBookHandler implements MessageHandlerInterface
{
    private BookRepository $repository;

    public function __construct(BookRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetBook $getBook): ?Book
    {
        return $this->repository->find($getBook->getId());
    }
}
