<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();
        $data = [
            'status' => 'error',
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof HttpExceptionInterface && $exception->getPrevious() instanceof ValidationFailedException) {
            $validationException = $exception->getPrevious();
            $violations = [];
            foreach ($validationException->getViolations() as $violation) {
                $violations[$violation->getPropertyPath()] = $violation->getMessage();
            }

            $data['errors'] = $violations;
            $response->setData($data);
            $response->setStatusCode(400);

        }
        elseif ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->setData($data);
        }
        else {
            $response->setStatusCode(500);
            $data['message'] = 'Internal Server Error';

            if ($_ENV['APP_ENV'] === 'dev') {
                $data['debug_message'] = $exception->getMessage();
            }

            $response->setData($data);
        }

        $event->setResponse($response);
    }
}
