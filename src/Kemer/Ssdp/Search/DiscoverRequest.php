<?php
namespace Kemer\Ssdp\Search;

use Kemer\Ssdp\SsdpMessage;

class DiscoverRequest implements SearchRequestInterface
{

    /**
     * Contains a URL to the UPnP description of the root device
     *
     * @var string
     */
    protected $host = '239.255.255.250:1900';

    /**
     * Must be "ssdp:discover".
     *
     * @var string
     */
    protected $man = '"ssdp:discover"';

    /**
     * Search Target. Single URI.
     *
     * @var string
     */
    protected $st;

    /**
     * Maximum wait time in seconds.
     *
     * @var integer
     */
    protected $mx = 1;

    /**
     * DiscoverRequest constructor
     *
     * @param string $st Search target
     */
    public function __construct($st = null)
    {
        $this->setSt($st);
    }

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
     * Required by HTTP Extension Framework. Unlike the NTS and ST headers, the value
     * of the MAN header is enclosed in double quotes; it defines the scope (namespace)
     * of the extension. Must be "ssdp:discover".
     *
     * @param [type] $man
     */
    public function setMan($man)
    {
        $this->man = $man;
        return $this;
    }

    /**
     * Returns concatenation of OS name, OS version, UPnP/1.0, product name, and product version.
     *
     * @return string
     */
    public function getMan()
    {
        return $this->man;
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
     * Maximum wait time in seconds. Should be between 1 and 120 inclusive. Device responses
     * should be delayed a random duration between 0 and this many seconds to balance load
     * for the control point when it processes responses. This value may be increased if
     * a large number of devices are expected to respond. The MX value should not be increased
     * to accommodate network characteristics such as latency or propagation delay
     *
     * @param integer $mx
     */
    public function setMx($mx)
    {
        $this->mx = $mx;
        return $this;
    }

    /**
     * Returns maximum wait time in seconds.
     *
     * @return string
     */
    public function getMx()
    {
        return $this->mx;
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
            ->setSt($message->getHeader("ST")->getFieldValue())
            ->setMx($message->getHeader("MX")->getFieldValue());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $message = new SsdpMessage();
        $message->setMethod(SsdpMessage::METHOD_SEARCH)
            ->setUri('*')
            ->getHeaders()->addHeaders([
                'HOST' => $this->getHost(),
                'MAN' => $this->getMan(),
                'MX' => $this->getMx(),
                'ST' => $this->getSt()
            ]);
        return $message->toString();
    }
}
