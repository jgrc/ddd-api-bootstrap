<?php
namespace Jgrc\Bootstrap\Kernel\Utils;

use Assert\InvalidArgumentException;
use Jgrc\Bootstrap\Ddd\Exception\AssertException;
use Jgrc\Bootstrap\Ddd\Exception\ExistsException;
use Jgrc\Bootstrap\Ddd\Exception\NotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function  onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        switch (true) {
            case $exception instanceof AssertException:
                $event->setResponse(
                    $this->handleAssertException($exception)
                );
                break;
            case $exception instanceof NotFoundException:
                $event->setResponse(
                    $this->handleNotFoundException($exception)
                );
                break;
            case $exception instanceof ExistsException:
                $event->setResponse(
                    $this->handleExistsException($exception)
                );
                break;
            default:
                $event->setResponse(
                    $this->handleDefaultException($exception)
                );
        }
    }

    private function handleAssertException(AssertException $e)
    {
        return new JsonResponse(
            [
                'errors' => array_map(
                    function (InvalidArgumentException $error) {
                        return [
                            'id' => $error->getPropertyPath(),
                            'message' => $error->getMessage()
                        ];
                    },
                    $e->getErrorExceptions()
                )
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    private function handleNotFoundException(NotFoundException $e)
    {
        return new JsonResponse(
            [
                'message' => $e->getMessage(),
                'resource' => $e->resource(),
                'id' => $e->id()
            ],
            JsonResponse::HTTP_NOT_FOUND
        );
    }

    private function handleExistsException(ExistsException $e)
    {
        return new JsonResponse(
            [
                'message' => $e->getMessage(),
                'resource' => $e->resource(),
                'id' => $e->id()
            ],
            JsonResponse::HTTP_CONFLICT
        );
    }

    private function handleDefaultException(\Exception $e)
    {
        return new JsonResponse(
            [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ],
            JsonResponse::HTTP_SERVICE_UNAVAILABLE
        );
    }
}