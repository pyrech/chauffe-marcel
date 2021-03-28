<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsListener implements EventSubscriberInterface
{
    /** @var string[] A list of accepted domains that this lib is able to handle */
    private array $acceptedDomains;

    public function __construct(array $acceptedDomains)
    {
        $this->acceptedDomains = $acceptedDomains;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['handleCors', 1024],
            ],
            KernelEvents::RESPONSE => [
                ['handleCorsResponse', 1024],
            ],
        ];
    }

    /**
     * Handle a pre-flight request (OPTIONS).
     */
    public function handleCors(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$request->headers->has('Origin')) {
            return;
        }

        if (Request::METHOD_OPTIONS !== $request->getMethod()) {
            return;
        }

        $event->setResponse($this->addHeaders($request, new Response()));
        $event->stopPropagation();
    }

    /**
     * Handle a classic request.
     */
    public function handleCorsResponse(ResponseEvent $event): void
    {
        $response = $this->addHeaders($event->getRequest(), $event->getResponse());
        $event->setResponse($response);
    }

    /**
     * Add CORS headers given the specific origin.
     */
    public function addHeaders(Request $request, Response $response): Response
    {
        $origin = $request->headers->get('Origin');

        if (!in_array($origin, $this->acceptedDomains, true)) {
            return $response;
        }

        $response->headers->add([
            'Access-Control-Allow-Methods' => $request->headers->get('Access-Control-Request-Method', 'GET'),
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => $request->headers->get('Access-Control-Request-Headers', '*'),
        ]);

        return $response;
    }
}
