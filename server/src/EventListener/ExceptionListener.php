<?php

namespace ChauffeMarcel\EventListener;

use ChauffeMarcel\MarcelException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleException', 1024],
            ],
        ];
    }

    public function handleException(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || strpos($request->getPathInfo(), '/api') !== 0) {
            return;
        }

        $exception = $event->getException();

        $data = [
            'error' => true,
            'message' => $exception instanceof MarcelException
                ? $exception->getMessage()
                : ($exception instanceof HttpExceptionInterface ? $exception->getMessage() : 'Internal server error'),
        ];

        $this->logger->error($exception->getMessage());

        $event->setResponse(new JsonResponse(
            $data,
            $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
            $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : []
        ));
        $event->stopPropagation();
    }
}
