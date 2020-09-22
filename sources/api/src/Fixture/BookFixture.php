<?php

namespace Ddd\Fixture;

use Ddd\Domain\Entity\Book;
use Ddd\Domain\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $category = new Category();
        $category->setName('Action');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Drama');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Noir');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Crime');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Romance');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Historical');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Faith');
        $manager->persist($category);

        $category = new Category();
        $category->setName('Psychology');
        $manager->persist($category);
        $manager->flush();

        /** @var Category[] $categories */
        $categories = $manager->getRepository(Category::class)
            ->findAll();

        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setName($faker->text(30));
            $assignCategoryCount = mt_rand(1, 3);
            while($assignCategoryCount--) {
                $book->addCategory($categories[mt_rand(0, count($categories)-1)]);
            }
            $manager->persist($book);
        }

        $manager->flush();
    }
}
