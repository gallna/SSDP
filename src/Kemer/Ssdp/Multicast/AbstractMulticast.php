<?php
namespace Kemer\Ssdp\Multicast;

abstract class AbstractMulticast
{
    const MULTICAST_IP = "239.255.255.250";
    const MULTICAST_PORT = "1900";

    /**
     * @var string
     */
    private $remoteIp;

    /**
     * @var integer
     */
    private $remotePort;

    /**
     * Create, bind and return multicast socket connection
     *
     * @return resource
     */
    abstract public function getSocket();

    /**
     * Close socket
     */
    abstract public function close();

    /**
     * Return current multicast message (if any)
     *
     * @return string|null
     */
    public function receive()
    {
        socket_recvfrom($this->getSocket(), $message, 1024, 0, $remoteIp, $remotePort);
        if (!empty($message)) {
            $this->remoteIp = $remoteIp;
            $this->remotePort = $remotePort;
            e("#---- received ($remoteIp:$remotePort) ----", "light_purple");
            e($message, "purple");
            return $message;
        }
    }

    /**
     * Response to last multicast message
     *
     * @param string $data
     * @return array
     */
    public function sendResponse($data)
    {
        if (!$this->remoteIp || !$this->remotePort) {
            throw \InvalidArgumentException(
                sprintf("Remote ip or port undefined '%s:%s' ", $this->remoteIp, $this->remotePort)
            );
        }
        return $this->send($data, $this->remoteIp, $this->remotePort);
    }

    /**
     * Send multicast request
     *
     * @param string $data
     * @return array
     */
    public function sendRequest($data)
    {
        if (!is_string($data)) {
            throw \InvalidArgumentException(
                sprintf("Socket send data must be a string '%s' given", gettype($data))
            );
        }
        $this->send($data);
        $response = [];
        while (($received = $this->receive()) !== null) {
            $response[] = $received;
        }
        return $response;
    }

    /**
     * Send data to multicast
     *
     * @param string $data
     * @param string $ip
     * @param integer $port
     * @return array
     */
    private function send($data, $ip = self::MULTICAST_IP, $port = self::MULTICAST_PORT)
    {
        if (!is_string($data)) {
            throw \InvalidArgumentException(
                sprintf("Socket send data must be a string '%s' given", gettype($data))
            );
        }
        e("#---- send ($ip:$port) ----", "light_green");
        e($data, "green");
        socket_sendto($this->getSocket(), $data, strlen($data), 0, $ip, $port);
    }

    /**
     * Returns last multicast message ip:port
     *
     * @return string
     */
    public function getSender()
    {
        return $this->remoteIp.":".$this->remotePort;
    }

    /**
     * Close socket
     *
     * @return $this
     */
    public function __destruct()
    {
        $this->close();
    }
}
