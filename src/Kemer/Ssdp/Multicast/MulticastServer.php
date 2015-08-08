<?php
namespace Kemer\Ssdp\Multicast;

class MulticastServer extends AbstractMulticast
{
    /**
     * @var resource
     */
    private $socket;

    /**
     * {@inheritDoc}
     */
    public function getSocket()
    {
        if (!$this->socket) {
            if (false === ($this->socket = socket_create(AF_INET, SOCK_DGRAM, getprotobyname('udp')))) {
                echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
            }
            socket_set_option($this->socket, 1, 6, true);
            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=> 1, 'usec'=>'0'));
            //socket_set_option($this->socket, SOL_SOCKET,SO_REUSEADDR, 1);
            // socket_set_nonblock($this->socket);
            socket_set_option($this->socket, IPPROTO_IP, MCAST_JOIN_GROUP, array("group" => '239.255.255.250', "interface" => 0));

            if (!socket_bind($this->socket, static::MULTICAST_IP, static::MULTICAST_PORT)) {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                throw new \Exception("Could not bind socket : [$errorcode] $errormsg \n");
            }
        }
        return $this->socket;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
    }
}
