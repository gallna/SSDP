<?php
namespace Kemer\Ssdp\Listener;

use Kemer\Ssdp\Notify;
use Kemer\Ssdp\SsdpEvent;
use Kemer\Ssdp\NotifyEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class NotifyListener
{
    /**
     * Dispatch ssdp:alive and ssdp:byebye event from NOTIFY request
     *
     * @param SsdpEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     * @return void
     */
    public function onNotify(SsdpEvent $event, $eventName, EventDispatcher $dispatcher)
    {
        $request = $event->getRequest();
        switch ($request->getHeader("NTS")->getFieldValue()) {
            case 'ssdp:alive':
                $notifyEvent = (new NotifyEvent())
                    ->setRequest($request)
                    ->setNotifyRequest(Notify\AliveRequest::fromString((string)$request));
                $dispatcher->dispatch(SsdpEvent::ALIVE, $notifyEvent);
                break;
            case 'ssdp:byebye':
                $notifyEvent = (new NotifyEvent())
                    ->setRequest($request)
                    ->setNotifyRequest(Notify\ByebyeRequest::fromString((string)$request));
                $dispatcher->dispatch(SsdpEvent::BYEBYE, $notifyEvent);
                break;
        }
    }
}
