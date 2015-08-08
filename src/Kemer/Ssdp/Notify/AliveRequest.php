<?php
namespace Kemer\Ssdp\Notify;

use Kemer\Ssdp\SsdpMessage;

class AliveRequest extends AbstractNotifyRequest
{
    /**
     * Required max-age directive that specifies number of seconds the advertisement
     * is valid
     *
     * @var integer
     */
    protected $cacheControl;

    /**
     * Contains a URL to the UPnP description of the root device
     *
     * @var string
     */
    protected $location;

    /**
     * OS/version UPnP/1.0 product/version
     *
     * @var string
     */
    protected $server;

    public function __construct()
    {
        $this->setNts('ssdp:alive');
    }

    /**
     * Must have max-age directive that specifies number of seconds the advertisement
     * is valid. After this duration, control points should assume the device (or service)
     * is no longer available. Should be greater than or equal to 1800 seconds (30 minutes).
     *
     * @param integer $cacheControl
     */
    public function setCacheControl($cacheControl)
    {
        $this->cacheControl = $cacheControl;
        return $this;
    }

    /**
     * Returns max-age directive that specifies number of seconds the advertisement
     * is valid.
     *
     * @return string
     */
    public function getCacheControl()
    {
        return "max-age=" . $this->cacheControl;
    }

    /**
     * Contains a URL to the UPnP description of the root device. Normally the host portion
     * contains a literal IP address rather than a domain name in unmanaged networks.
     *
     * @param string $location URL
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Returns a URL to the UPnP description of the root device
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Concatenation of OS name, OS version, UPnP/1.0, product name, and product version.
     * Specified by UPnP vendor. String. Must accurately reflect the version number of
     * the UPnP Device Architecture supported by the device. Control points must be prepared
     * to accept a higher version number than the control point itself implements.
     * For example, control points implementing UDA version 1.0 will be able to interoperate
     * with devices implementing UDA version 1.1.
     *
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * Returns concatenation of OS name, OS version, UPnP/1.0, product name, and product version.
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * A factory that produces a Request object from string
     *
     * @param  string $string
     * @throws Zend\Http\Exception\InvalidArgumentException
     * @return DiscoverRequest
     */
    public static function fromString($string)
    {
        $message = SsdpMessage::fromString($string);
        return (new static())
            ->setHost($message->getHeader("HOST")->getFieldValue())
            ->setNt($message->getHeader("NT")->getFieldValue())
            ->setUsn($message->getHeader("USN")->getFieldValue())
            ->setServer($message->getHeader("Server")->getFieldValue())
            ->setLocation($message->getHeader("Location")->getFieldValue())
            ->setCacheControl($message->getHeader("Cache-Control")->getFieldValue());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $message = new SsdpMessage();
        $message->setMethod(SsdpMessage::METHOD_NOTIFY)
            ->setUri('*')
            ->getHeaders()->addHeaders([
                "HOST" => $this->getHost(),
                "NT" => $this->getNt(),
                "USN" => $this->getUsn(),
                "Server" => $this->getServer(),
                "Location" => $this->getLocation(),
                "Cache-Control" => $this->getCacheControl()
            ]);
        return $message->toString();
    }
}
