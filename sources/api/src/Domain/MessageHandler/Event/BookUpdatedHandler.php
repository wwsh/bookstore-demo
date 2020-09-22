<?php

namespace Ddd\Domain\MessageHandler\Event;

use Ddd\Domain\Message\Event\BookUpdated;

class BookUpdatedHandler
{
    public function __invoke(BookUpdated $bookUpdated): void
    {
        // @todo ...
    }
}
