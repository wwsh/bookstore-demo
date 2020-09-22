<?php

namespace Ddd\Domain\MessageHandler\Command;

use Ddd\Domain\Message\Command\CreateBook;
use Ddd\Domain\Message\Event\BookCreated;
use Ddd\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateBookHandler implements MessageHandlerInterface
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

    public function __invoke(CreateBook $createBook): array
    {
        $book = $createBook->getBook();

        $this->repository->persist($book);

        $this->eventBus->dispatch(new BookCreated($book));

        return ['result' => true];
    }
}
