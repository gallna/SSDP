<?php
namespace Kemer\Ssdp\Search;

use Zend\Http\Response;
use Zend\Http\Header\GenericHeader;

class DiscoverResponse implements SearchResponseInterface
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
     * Concatenation of OS name, OS version, UPnP/1.0, product name, and product version.
     *
     * @var string
     */
    protected $server;

    /**
     * Search Target. Single URI.
     *
     * @var string
     */
    protected $st;

    /**
     * Unique Service Name.
     *
     * @var string
     */
    protected $usn;

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
     * Search Target. Single URI.
     *
     * @param string $st
     */
    public function setSt($st)
    {
        $this->st = $st;
        return $this;
    }

    /**
     * Returns Search Target.
     *
     * @return string
     */
    public function getSt()
    {
        return $this->st;
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


    /**
     * A factory that produces a Request object from string
     *
     * @param  string $string
     * @throws Zend\Http\Exception\InvalidArgumentException
     * @return DiscoverRequest
     */
    public static function fromString($string)
    {
        $response = Response::fromString($string);
        $headers = $response->getHeaders();
        return (new static())
            ->setCacheControl($headers->get("CACHE-CONTROL")->getFieldValue())
            ->setLocation($headers->get("LOCATION")->getFieldValue())
            ->setServer($headers->get("SERVER")->getFieldValue())
            ->setSt($headers->get("ST")->getFieldValue())
            ->setUsn($headers->get("USN")->getFieldValue());
    }

    /**
     * @return string
     */
    public function toString()
    {
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->getHeaders()->addHeaders([
            'CACHE-CONTROL' => $this->getCacheControl(),
            'DATE' => 'Sat, 08 Aug 2015 14:22:48 GMT',
            //'DATE' => (new \DateTime())->format(\DateTime::RFC1123),//Thu, 06 Aug 2015 23:01:17 GMT
            new GenericHeader("EXT"),
            'LOCATION' => $this->getLocation(),
            'SERVER' => $this->getServer(),
            'ST' => $this->getSt(),
            'USN' => (string)$this->getUsn(),
        ]);
        return $response->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

}
