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

        if (!$event->isMasterRequest()) {
            return;
        }

        $exception = $event->getException();
        $code = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = $exception instanceof MarcelException
            ? $exception->getMessage()
            : ($exception instanceof HttpExceptionInterface ? $exception->getMessage() : 'Internal server error');
        $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];

        if (strpos($request->getPathInfo(), '/api') !== 0) {
            $html = <<<HTML
<html>
<head><title>$message</title></head>
<body>$message</body>
</html>
HTML;

            $response = new Response($html, $code, $headers);
        } else {
            $data = [
                'error' => true,
                'message' => $message,
            ];

            $response = new JsonResponse($data, $code, $headers);
        }

        $this->logger->error($exception->getMessage());

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
