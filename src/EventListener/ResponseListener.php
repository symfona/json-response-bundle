<?php declare(strict_types=1);

namespace Symfona\Bundle\JsonResponseBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ResponseListener
{
    public function onKernelView(ViewEvent $event): void
    {
        if ($event->hasResponse()) {
            return;
        }

        $controllerResult = $event->getControllerResult();

        if (null === $controllerResult) {
            $statusCode = Response::HTTP_NO_CONTENT;
        } elseif (Request::METHOD_POST === $event->getRequest()->getMethod()) {
            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_OK;
        }

        $this->createResponse($event, $controllerResult, $statusCode);
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HttpExceptionInterface) {
            $statusCode = $throwable->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // validation errors

        $this->createResponse($event, ['message' => $throwable->getMessage()], $statusCode);
    }

    private function createResponse(RequestEvent $event, mixed $data, int $statusCode): void
    {
        $event->setResponse(new JsonResponse($data, $statusCode));
    }
}
