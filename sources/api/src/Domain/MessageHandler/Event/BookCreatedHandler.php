<?php

namespace Ddd\Domain\MessageHandler\Event;

use Ddd\Domain\Message\Event\BookCreated;

class BookCreatedHandler
{
    public function __invoke(BookCreated $bookCreated): void
    {
        // @todo ...
    }
}
