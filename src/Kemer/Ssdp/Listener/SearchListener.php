<?php
namespace Kemer\Ssdp\Listener;

use Kemer\Ssdp\SsdpEvent;
use Kemer\Ssdp\SearchEvent;
use Kemer\Ssdp\Search\DiscoverRequest;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SearchListener
{
    /**
     * Dispatch ssdp:discover event from M-SEARCH request
     *
     * @param SsdpEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     * @return void
     */
    public function onSearch(SsdpEvent $event, $eventName, EventDispatcher $dispatcher)
    {
        $searchEvent = (new SearchEvent())
            ->setRequest($request = $event->getRequest())
            ->setSearchRequest($discoverRequest = DiscoverRequest::fromString((string)$request));
        $dispatcher->dispatch(SsdpEvent::DISCOVER, $searchEvent);

        // Send delayed responses to multicast
        foreach ($searchEvent->getSearchResponses() as $response) {
            sleep(
                $discoverRequest->getMx() > 3
                    ? $discoverRequest->getMx() / 2
                    : $discoverRequest->getMx()
            );
            $event->setResponse($response->toString());
            $dispatcher->dispatch(SsdpEvent::RESPONSE, $event);
        }
    }
}
