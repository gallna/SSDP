<?php
namespace Kemer\SSDP\Discover;

use Kemer\SSDP;
use Kemer\UPnP\Devices;

class Discover
{
    protected $socket;

    public function __construct(SSDP\Socket $socket)
    {
        $this->socket = $socket;
    }

    public function getRootDevices()
    {
        return $this->discover(Devices::ROOT_DEVICE);
        return $roots;
    }

    public function getMediaRenderers($uid = null)
    {
        $discovered = $this->discover(
            $uid
                ? sprintf("uuid:%s", $uid)
                : Devices::MEDIA_RENDERER
        );
        return $uid ? reset($discovered) : $discovered;
    }

    public function getMediaServers($uid = null)
    {
        $discovered = $this->discover(
            $uid
                ? sprintf("uuid:%s", $uid)
                : Devices::MEDIA_SERVER
        );
        return $uid ? reset($discovered) : $discovered;
    }


    public function discover($st = CLIENT::ALL, $mx = 2)
    {
        $request = implode("\r\n", [
            'M-SEARCH * HTTP/1.1',
            'HOST: 239.255.255.250:1900',
            'MAN: "ssdp:discover"',
            "MX: $mx",
            "ST: $st",
            'USER-AGENT: Roku/DVP-5.5 (025.05E00410A)'
        ])."\r\n\r\n";
        $response = $this->socket->send($request);
        return array_map([$this, 'parseResponse'], $response);
    }


    private function parseResponse($response)
    {
        $data = [];
        $responseArray = array_filter(explode("\r\n", $response));
        $data["http"] = substr($http = array_shift($responseArray), 0, strpos($http, " "));
        foreach ($responseArray as $line) {
            if (($pos = strpos($line, ":")) !== false) {
                $data[strtolower(substr($line, 0, $pos))] = trim(substr($line, $pos+1));
            } else {
                $data[] = $line;
            }
        };
        //return $data;
        return new SSDP\Response\DiscoveryResponse($data);
    }
}
