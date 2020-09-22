<?php

namespace Ddd\Domain\MessageHandler\Command;

use Ddd\Domain\Message\Command\UpdateBook;
use Ddd\Domain\Message\Event\BookUpdated;
use Ddd\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class UpdateBookHandler
{
    private BookRepository $repository;
    private MessageBusInterface $eventBus;

    public function __construct(
        BookRepository $repository,
        MessageBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(UpdateBook $updateBook): array
    {
        $book = $updateBook->getBook();

        $this->repository->persist($book);

        $this->eventBus->dispatch(new BookUpdated($book));

        return ['result' => true];
    }
}