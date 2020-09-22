<?php

namespace Ddd\Domain\MessageHandler\Event;

use Ddd\Domain\Message\Event\BookDeleted;

class BookDeletedHandler
{
    public function __invoke(BookDeleted $bookDeleted): void
    {
        // @todo ...
    }
}
