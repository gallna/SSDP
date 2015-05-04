<?php
namespace Kemer\SSDP;

class Socket
{
    private $socket;
    public function __construct()
    {

    }

    public function getRootDevices()
    {
        $roots = $this->discover(Client::ROOT_DEVICE);
        return $roots;
    }

    public function getMediaRenderers()
    {
        $renderers = $this->discover(Client::MEDIA_RENDERER);
        return array_map(function ($spec) {
            return new MediaRenderer($spec);
        }, $renderers);
    }

    public function getMediaServers($usn = null)
    {
        $servers = $this->discover($usn ?: Client::MEDIA_SERVER);
        return array_map(function ($spec) {
            return new MediaServer($spec);
        }, $servers);
        return $servers;
    }

    public function getSocket()
    {
        if (!$this->socket) {
            $this->socket = socket_create(AF_INET, SOCK_DGRAM, 0);
            socket_set_option($this->socket, 1, 6, true);
            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=> 2, 'usec'=>'0'));
        }
        return $this->socket;
    }

    /**
     * Close socket
     *
     * @return $this
     */
    public function close()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
        return $this;
    }

    /**
     * Close socket
     *
     * @return $this
     */
    public function __destruct()
    {
        if (is_resource($this->socket)) {
            socket_close($this->socket);
        }
    }

    /**
     * [search description]
     * @param  string  $st         search target
     * @param  integer $mx         seconds to delay response
     * @param  [type]  $from       [description]
     * @param  [type]  $port       [description]
     * @param  string  $sockTimout [description]
     * @return [type]              [description]
     */
    public function send($request, $ip = '239.255.255.250', $port = 1900)
    {
        socket_sendto($this->getSocket(), $request, strlen($request), 0, $ip, $port);
        return $this->getResponse();
    }

    /**
     * [search description]
     * @param  string  $st         search target
     * @param  integer $mx         seconds to delay response
     * @param  [type]  $from       [description]
     * @param  [type]  $port       [description]
     * @param  string  $sockTimout [description]
     * @return [type]              [description]
     */
    public function getResponse()
    {
        $ip = $port = null;
        $response = array();
        do {
            $buf = null;
            socket_recvfrom($this->getSocket(), $buf, 1024, MSG_WAITALL, $ip, $port);
            if(!is_null($buf)){
                $response[] = $buf;
            }
        } while(!is_null($buf));

        return $response;
    }
}
