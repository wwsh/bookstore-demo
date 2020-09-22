<?php

namespace Ddd\Domain\MessageHandler\Command;

use Ddd\Domain\Message\Command\DeleteBook;
use Ddd\Domain\Message\Event\BookDeleted;
use Ddd\Domain\Repository\BookRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteBookHandler
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

    public function __invoke(DeleteBook $deleteBook): array
    {
        $id = $deleteBook->getId();

        $this->repository->delete($id);

        $this->eventBus->dispatch(new BookDeleted($id));

        return ['result' => true];
    }
}
