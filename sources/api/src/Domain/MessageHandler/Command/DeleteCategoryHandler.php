<?php

namespace Ddd\Domain\MessageHandler\Command;

use Ddd\Domain\Message\Command\DeleteCategory;
use Ddd\Domain\Message\Event\CategoryDeleted;
use Ddd\Domain\Repository\CategoryRepository;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteCategoryHandler
{
    private CategoryRepository $repository;
    private MessageBusInterface $eventBus;

    public function __construct(
        CategoryRepository $repository,
        MessageBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function __invoke(DeleteCategory $deleteCategory): array
    {
        $id = $deleteCategory->getId();

        $this->repository->delete($id);

        $this->eventBus->dispatch(new CategoryDeleted($id));

        return ['result' => true];
    }
}
