<?php

namespace ChauffeMarcel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthorizationCheckerListener implements EventSubscriberInterface
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['checkHeader', 1024],
            ],
        ];
    }

    public function checkHeader(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || strpos($request->getPathInfo(), '/api') !== 0) {
            return;
        }

        if (!$request->headers->has('Authorization')) {
            $event->setResponse(new Response('Unauthorized', Response::HTTP_UNAUTHORIZED));
            $event->stopPropagation();

            return;
        }

        $apiKey = $request->headers->get('Authorization');

        if ($apiKey !== $this->apiKey) {
            $event->setResponse(new Response('Unauthorized', Response::HTTP_UNAUTHORIZED));
            $event->stopPropagation();

            return;
        }
    }
}
