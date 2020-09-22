<?php

namespace Ddd\Domain\Message\Event;

use Ddd\Domain\Entity\Category;

class CategoryCreated
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}