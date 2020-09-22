<?php

namespace Ddd\Domain\Repository;

use Ddd\Domain\Entity\Category;

interface CategoryRepository
{
    public function findAll(int $offset, int $limit);

    public function find(int $id): ?Category;

    public function persist(Category $category): void;

    public function findByName(string $name): ?Category;

    public function delete(int $id): void;
}
