<?php

namespace Ddd\Domain\MessageHandler\Query;

use Ddd\Domain\Entity\Category;
use Ddd\Domain\Message\Query\GetCategory;
use Ddd\Domain\Repository\CategoryRepository;

class GetCategoryHandler
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetCategory $getCategory): ?Category
    {
        return $this->repository->find($getCategory->getId());
    }
}
