<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthorizationCheckerListener implements EventSubscriberInterface
{
    private string $apiKey;

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

    public function checkHeader(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest() || 0 !== strpos($request->getPathInfo(), '/api')) {
            return;
        }

        if (!$request->headers->has('Authorization')) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        $apiKey = $request->headers->get('Authorization');

        if ($apiKey !== $this->apiKey) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        }
    }
}
