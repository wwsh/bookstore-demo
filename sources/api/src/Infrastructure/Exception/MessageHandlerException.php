<?php

namespace Ddd\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class MessageHandlerException
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();
        if (!($exception instanceof HandlerFailedException)) {
            return;
        }

        // perhaps if we handle all would be better? @todo
        $exception = $exception->getNestedExceptions()[0];
        if (!$exception instanceof HttpException) {
            return;
        }

        $response = new JsonResponse(['error' => $exception->getMessage()]);
        $response->setStatusCode($exception->getStatusCode());
        $event->setResponse($response);
    }
}
