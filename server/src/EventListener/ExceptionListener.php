<?php

namespace ChauffeMarcel\EventListener;

use ChauffeMarcel\MarcelException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
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
                : 'Internal server error',
        ];

        $event->setResponse(new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR));
        $event->stopPropagation();
    }
}
