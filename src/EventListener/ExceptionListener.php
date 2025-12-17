<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;

class ExceptionListener
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $error = new ResponseErrorDTO();
        $error->reason = 'Error';
        $error->message = $exception->getMessage();

        $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        $response = new JsonResponse(
            $this->serializer->normalize($error, 'json'),
            $status
        );

        $event->setResponse($response);
    }
}