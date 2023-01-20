<?php

namespace App\EventListener;

use App\Service\Core\Api\ApiProblem;
use App\Service\Core\Api\ApiProblemException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener {

    public function onKernelException(ExceptionEvent $event)
    {
        if (strpos($event->getRequest()->getRequestUri(), "api/")) {
            $exception = $event->getThrowable();

            $code = $exception->getCode();


            if ($exception instanceof HttpExceptionInterface) {
                $code = $exception->getStatusCode();
            } else {
                $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            }

           
            

            //$return = json_encode(array());
            //$event->setResponse(new Response($return, $code, array('Content-Type' => 'application/json', 'Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Headers' => ' Content-Type')));

            $e = $event->getThrowable();

            if (!$e instanceof ApiProblemException) {
                $apiProblem = new ApiProblem($code, "error", $exception->getMessage());
            } else {
                $apiProblem = $e->getApiProblem();
            }


            $response = new JsonResponse(
                    $apiProblem->toArray(),
                    $apiProblem->getStatusCode()
            );
            $response->headers->set('Content-Type', 'application/problem+json');
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents() {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

}