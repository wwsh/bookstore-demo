<?php

namespace Ddd\Infrastructure\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

final class GeneralApiExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $e = $event->getException();

        if (!$e instanceof GeneralException) {
            return;
        }

        $message = sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine());

        $response = new JsonResponse(
            [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );

        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }
}
