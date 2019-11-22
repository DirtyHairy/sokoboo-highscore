<?php


namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class CacheControlSubscriber
 * @package App\EventSubscriber
 */
class CacheControlSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => ["onResponse", 0]];
    }

    /**
     * @param ResponseEvent $event
     */
    public function onResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->set("cache-control", "no-cache");
    }
}