<?php

namespace Ddd\Infrastructure\Persistence;

use Ddd\Domain\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BookRepository implements \Ddd\Domain\Repository\BookRepository
{
    private EntityRepository $repository;
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
        $this->repository = $entityManager->getRepository(Book::class);
    }

    public function find($id): ?Book
    {
        return $this->repository->find($id);
    }

    /**
     * @return Book[]
     */
    public function findAll(int $offset, int $limit)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    public function persist(Book $book): void
    {
        $this->manager->persist($book);
        $this->manager->flush();
    }

    public function delete(int $id): void
    {
        $this->manager->remove($this->find($id));
        $this->manager->flush();
    }
}
