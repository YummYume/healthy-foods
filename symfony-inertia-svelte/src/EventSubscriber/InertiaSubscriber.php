<?php

namespace App\EventSubscriber;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class InertiaSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly InertiaInterface $inertia, private readonly ParameterBagInterface $parameterBag)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 50],
            KernelEvents::EXCEPTION => ['onKernelException', 50],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->inertia->share('route', $event->getRequest()->attributes->get('_route'));
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $isDev = 'dev' === $this->parameterBag->get('app.env');

        if ($isDev) {
            return;
        }

        $exception = $event->getThrowable();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        if (\in_array($statusCode, [
            Response::HTTP_INTERNAL_SERVER_ERROR,
            Response::HTTP_SERVICE_UNAVAILABLE,
            Response::HTTP_NOT_FOUND,
            Response::HTTP_FORBIDDEN,
        ], true)) {
            $event->setResponse($this->inertia->render('Exception', ['status' => $statusCode])->setStatusCode($statusCode));
        }
    }
}
