<?php


namespace App\EventSubscriber;


use App\Constants\SessionHandling;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SessionHandlingSubscriber
 * @package App\Event
 */
class SessionHandlingSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::CONTROLLER => ["onKernelController", 0]];
    }

    /**
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event): void
    {
        /** @var SessionInterface $session */
        $session = $event->getRequest()->getSession();

        $session->start();

        if ($session->has(SessionHandling::SESSION_VARIABLE_ID)) {
            $session->set(SessionHandling::SESSION_VARIABLE_AUTHORIZED, true);
        } else {
            $session->set(SessionHandling::SESSION_VARIABLE_ID, md5(uniqid("", true)));
            $session->set(SessionHandling::SESSION_VARIABLE_AUTHORIZED, false);
        }
    }
}