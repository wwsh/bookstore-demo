<?php

namespace Ddd\Domain\MessageHandler\Query;

use Ddd\Domain\Message\Query\GetCategories;
use Ddd\Domain\Repository\CategoryRepository;

class GetCategoriesHandler
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetCategories $getCategories): array
    {
        return $this->repository->findAll($getCategories->getOffset(), $getCategories->getLimit());
    }
}
