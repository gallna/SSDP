<?php
namespace Kemer\Ssdp\Multicast;

class MulticastClient extends AbstractMulticast
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
