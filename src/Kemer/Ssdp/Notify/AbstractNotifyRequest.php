<?php
namespace Kemer\Ssdp\Notify;

abstract class AbstractNotifyRequest
{
    /**
     * Contains a URL to the UPnP description of the root device
     *
     * @var string
     */
    protected $host = '239.255.255.250:1900';

    /**
     * Notification Type. Single URI.
     *
     * @var string
     */
    protected $nt;

    /**
     * Notification Sub Type.. Single URI.
     *
     * @var string
     */
    protected $nts;

    /**
     * Advertisement UUID
     *
     * @var string
     */
    protected $usn;

    /**
     * Multicast channel and port reserved for SSDP by Internet Assigned Numbers Authority (IANA).
     * Must be 239.255.255.250:1900. If the port number (“:1900”) is omitted, the receiver
     * should assume the default SSDP port number of 1900.
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Returns multicast channel and port reserved for SSDP
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Notification Type. Single URI.
     *
     * @param string $st
     */
    public function setNt($nt)
    {
        $this->nt = $nt;
        return $this;
    }

    /**
     * Returns Notification Type.
     *
     * @return string
     */
    public function getNt()
    {
        return $this->nt;
    }

    /**
     * Notification Sub Type. Single URI.
     *
     * @param string $st
     */
    public function setNts($nts)
    {
        $this->nts = $nts;
        return $this;
    }

    /**
     * Returns Notification Sub Type.
     *
     * @return string
     */
    public function getNts()
    {
        return $this->nts;
    }

    /**
     * Unique Service Name.
     *
     * @param string $usn URI
     */
    public function setUsn($usn)
    {
        $this->usn = $usn;
        return $this;
    }

    /**
     * Returns Unique Service Name.
     *
     * @return string
     */
    public function getUsn()
    {
        return $this->usn;
    }
}
