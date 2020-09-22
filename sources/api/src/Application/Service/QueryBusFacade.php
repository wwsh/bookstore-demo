<?php

namespace Ddd\Application\Service;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBusFacade
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @param object|Envelope $query
     *
     * @return mixed The handler returned value
     */
    public function query($query)
    {
        return $this->handle($query);
    }
}

