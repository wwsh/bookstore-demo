<?php

namespace Ddd\Domain\MessageHandler\Command;

use Ddd\Domain\Message\Command\CreateCategory;
use Ddd\Domain\Message\Event\CategoryCreated;
use Ddd\Domain\Repository\CategoryRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateCategoryHandler  implements MessageHandlerInterface
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

    public function __invoke(CreateCategory $createCategory): array
    {
        $category = $createCategory->getCategory();

        $this->repository->persist($category);

        $this->eventBus->dispatch(new CategoryCreated($category));

        return ['result' => true];
    }
}