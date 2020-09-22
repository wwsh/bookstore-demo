<?php

namespace Ddd\Domain\Message\Command;

use Ddd\Domain\Entity\Category;

class CreateCategory
{
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}