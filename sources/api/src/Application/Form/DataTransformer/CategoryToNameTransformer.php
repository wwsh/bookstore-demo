<?php

namespace Ddd\Application\Form\DataTransformer;

use Ddd\Domain\Entity\Category;
use Ddd\Domain\Repository\CategoryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CategoryToNameTransformer implements DataTransformerInterface
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms entities to names.
     * @param Category[] $categories
     * @return array
     */
    public function transform($categories)
    {
        if (!$categories) {
            return [];
        }

        return collect($categories)
            ->map(fn (Category $category) => $category->getName())
            ->all();
    }

    /**
     * Transforms names to entities.
     *
     * @param  array $names
     * @return Category[]|null
     * @throws TransformationFailedException if not found
     */
    public function reverseTransform($names)
    {
        if (!$names) {
            return null;
        }

        $result = [];

        foreach ($names as $name) {
            $category = $this->repository->findByName($name);

            if (null === $category) {
                // causes a validation error
                // this message is not shown to the user
                // see the invalid_message option
                throw new TransformationFailedException(sprintf(
                    'An category with name "%s" does not exist!',
                    $names
                ));
            }

            $result[] = $category;
        }

        return $result;
    }
}
