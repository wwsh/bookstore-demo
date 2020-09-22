<?php

namespace Ddd\Infrastructure\Persistence;

use Ddd\Domain\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CategoryRepository implements \Ddd\Domain\Repository\CategoryRepository
{
    private EntityManagerInterface $manager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
        $this->repository = $entityManager->getRepository(Category::class);
    }

    public function find($id): ?Category
    {
        return $this->repository->find($id);
    }

    /**
     * @return Category[]
     */
    public function findAll(int $offset, int $limit)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    public function findByName(string $name): ?Category
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function persist(Category $category): void
    {
        $this->manager->persist($category);
        $this->manager->flush();
    }

    public function delete(int $id): void
    {
        $this->manager->remove($this->find($id));
        $this->manager->flush();
    }
}
