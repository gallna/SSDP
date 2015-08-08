<?php
namespace Kemer\Ssdp;

use Symfony\Component\EventDispatcher\Event;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

class SsdpEvent extends Event
{
    const SEARCH = 'M-SEARCH';
    const NOTIFY = 'NOTIFY';
    const RESPONSE = 'RESPONSE';
    const ALIVE = 'sspd:alive';
    const BYEBYE = 'sspd:byebye';
    const DISCOVER = 'sspd:discover';
    const ALL = 'ssdp:all';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var string
     */
    protected $sender;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request = null, $sender = null)
    {
        if (!is_null($request)) {
            $this->setRequest($request);
        }
        $this->sender = $sender;
    }

    /**
     * Get request
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param RequestInterface $request
     * @return $this
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get response
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Response
     *
     * @param ResponseInterface|string $responses
     * @return $this
     */
    public function setResponse($response)
    {
        if ($response instanceof ResponseInterface) {
            $response = $response->toString();
        }
        if (!is_string($response)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Response must be a string or instance of ResponseInterface, '%s' given",
                    gettype($response)
                )
            );
        }
        $this->response = $response;
        return $this;
    }

    /**
     * Returns last multicast message sender (ip:port)
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }
}
