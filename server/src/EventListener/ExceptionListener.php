<?php

namespace App\EventListener;

use App\MarcelException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private LoggerInterface $logger;

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

    public function handleException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        $throwable = $event->getThrowable();
        $code = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = $throwable instanceof MarcelException
            ? $throwable->getMessage()
            : ($throwable instanceof HttpExceptionInterface ? $throwable->getMessage() : 'Internal server error');
        $headers = $throwable instanceof HttpExceptionInterface ? $throwable->getHeaders() : [];

        if (0 !== strpos($request->getPathInfo(), '/api')) {
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

        $this->logger->error($throwable->getMessage());

        $event->setResponse($response);
        $event->stopPropagation();
    }
}
