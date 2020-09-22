<?php

namespace Ddd\Domain\MessageHandler\Event;

use Ddd\Domain\Message\Event\CategoryDeleted;

class CategoryDeletedHandler
{
    public function __invoke(CategoryDeleted $categoryDeleted): void
    {
        // @todo ...
    }
}
