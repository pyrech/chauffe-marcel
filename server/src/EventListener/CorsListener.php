<?php

namespace ChauffeMarcel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsListener implements EventSubscriberInterface
{
    /** @var string[] A list of valid domains that this lib is able to handle */
    private $validDomains;

    public function __construct(array $validDomains)
    {
        $this->validDomains = $validDomains;
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
     * Handle a pre-flight request (OPTIONS)
     */
    public function handleCors(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$request->headers->has('Origin')) {
            return;
        }

        if ($request->getMethod() !== Request::METHOD_OPTIONS) {
            return;
        }

        $event->setResponse($this->addHeaders($request, new Response()));
        $event->stopPropagation();
    }

    /**
     * Handle a classic request
     */
    public function handleCorsResponse(FilterResponseEvent $event)
    {
        $response = $this->addHeaders($event->getRequest(), $event->getResponse());
        $event->setResponse($response);
    }

    /**
     * Add CORS headers given the specific origin
     */
    public function addHeaders(Request $request, Response $response): Response
    {
        $origin = $request->headers->get('Origin');

        if (!in_array($origin, $this->validDomains, true)) {
            return $response;
        }

        $response->headers->add([
            'Access-Control-Allow-Methods'     => $request->headers->get('Access-Control-Request-Method', 'GET'),
            'Access-Control-Allow-Origin'      => $origin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers'     => $request->headers->get('Access-Control-Request-Headers', '*'),
        ]);

        return $response;
    }
}
