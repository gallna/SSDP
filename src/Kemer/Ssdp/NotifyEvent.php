<?php
namespace Kemer\Ssdp;

use Kemer\Ssdp\Notify\AbstractNotifyRequest;

class NotifyEvent extends SsdpEvent
{
    /**
     * @var AbstractNotifyRequest
     */
    protected $notifyRequest;

    /**
     * Get notify request
     *
     * @param AbstractNotifyRequest $request
     * @return $this
     */
    public function setNotifyRequest(AbstractNotifyRequest $request)
    {
        $this->notifyRequest = $request;
        return $this;
    }

    /**
     * Get request
     *
     * @return SearchRequestInterface
     */
    public function getNotifyRequest()
    {
        return $this->notifyRequest;
    }
}
