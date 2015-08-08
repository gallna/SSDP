<?php
include_once dirname(__DIR__).'/vendor/autoload.php';

use Kemer\Ssdp\Listener\LogListener;
use Kemer\Ssdp\Ssdp;
use Kemer\Ssdp\Multicast\MulticastServer;
use Kemer\Ssdp\SsdpEvent;
use Kemer\Ssdp\Listener;
use Symfony\Component\EventDispatcher\EventDispatcher;

// create event dispatcher
$eventDispatcher = new EventDispatcher();

// create SSDP server
$ssdp = new Ssdp(new MulticastServer(), $eventDispatcher);

// Add event listeners
$eventDispatcher->addListener(SsdpEvent::NOTIFY, [new Listener\NotifyListener(), "onNotify"]);
$eventDispatcher->addListener(SsdpEvent::SEARCH, [new Listener\SearchListener(), "onSearch"]);
$eventDispatcher->addListener(SsdpEvent::DISCOVER, [new Listener\DiscoverListener(), "onDiscover"]);

// Add log-listener to see an events
$eventDispatcher->addSubscriber(new LogListener());

$ssdp->run();
