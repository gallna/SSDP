<?php
namespace Kemer\Ssdp;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Ssdp
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var Multicast\MulticastServer
     */
    protected $multicast;

    /**
     * Constructor
     *
     * @param null|EventDispatcher $eventDispatcher
     */
    public function __construct(Multicast\MulticastServer $multicast, EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->multicast = $multicast;
    }

    /**
     * Get event dispatcher
     *
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * Returns multicast server
     *
     * @return Multicast\MulticastServer
     */
    public function getMulticast()
    {
        return $this->multicast;
    }

    /**
     * Run the SSDP event
     *
     * @return void
     */
    public function run()
    {
        $this->getEventDispatcher()->addListener(SsdpEvent::RESPONSE, [$this, "onResponse"]);
        while (1) {
            $message = $this->getMulticast()->receive();
            if (!empty($message)) {
                $response = $this->onMessage($message, $this->getMulticast()->getSender());
            }
        }
        $this->getMulticast()->close();
    }

    /**
     * Create and dispatch SSDP message
     *
     * @param string $message
     * @return void
     */
    public function onMessage($message, $sender)
    {
        $event = new SsdpEvent($request = SsdpMessage::fromString($message), $sender);
        switch ($request->getMethod()) {
            case "M-SEARCH":
                $this->getEventDispatcher()->dispatch(SsdpEvent::SEARCH, $event);
                break;
            case "NOTIFY":
                $this->getEventDispatcher()->dispatch(SsdpEvent::NOTIFY, $event);
                break;
        }
    }

    /**
     * Send response for current message
     *
     * @param SsdpEvent $event
     * @return void
     */
    public function onResponse(SsdpEvent $event)
    {
        $this->getMulticast()->sendResponse($event->getResponse());
    }
}
