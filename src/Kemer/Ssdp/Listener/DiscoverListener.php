<?php
namespace Kemer\Ssdp\Listener;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Kemer\Ssdp\SearchEvent;

/**
 * ST: Field value contains Search Target
 *
 * - ssdp:all
 * Search for all devices and services.
 *
 * - upnp:rootdevice
 * Search for root devices only.
 *
 * - uuid:device-UUID
 * Search for a particular device. device-UUID specified by UPnP vendor. MANDATORY UUID format
 *
 * - urn:schemas-upnp-org:device:deviceType:ver
 * - urn:schemas-upnp-org:service:serviceType:ver
 * Search for any device|service of this type where deviceType|serviceType and ver are defined
 * by the UPnP Forum working committee.
 *
 * - urn:domain-name:device:deviceType:ver
 * - urn:domain-name:service:serviceType:ver
 *
 * Search for any device|service of this type where domain-name, deviceType|serviceType
 * and ver are defined by the UPnP vendor and ver specifies the highest supported version
 * of the device type. Period characters in the Vendor Domain Name MUST be replaced with
 * hyphens in accordance with RFC 2141.
 */

class DiscoverListener
{
    /**
     * Dispatch ssdp:discover event from M-SEARCH request
     *
     * @param SearchEvent $event
     * @param string $eventName
     * @param EventDispatcherInterface $dispatcher
     * @return void
     */
    public function onDiscover(SearchEvent $event, $eventName, EventDispatcher $dispatcher)
    {
        $target = $event->getSearchRequest()->getSt();
        $dispatcher->dispatch($target, $event);
    }
}
