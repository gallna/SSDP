<?php
include_once dirname(__DIR__).'/vendor/autoload.php';

use Kemer\Ssdp\Multicast\MulticastClient;
use Kemer\Ssdp\Search\DiscoverRequest;
use Kemer\Ssdp\Search\DiscoverResponse;
use Kemer\Ssdp\SsdpEvent;

// The multicast client
$multicastClient = new MulticastClient();

// create discovery request
$discoverRequest = new DiscoverRequest(SsdpEvent::ALL);

// send request
$discovered = $multicastClient->sendRequest((string)$discoverRequest);

// iterate over discovery responses
foreach($discovered as $response) {
    $discovery = DiscoverResponse::fromString($response);
    echo $discovery->getUsn()."\n";
}

