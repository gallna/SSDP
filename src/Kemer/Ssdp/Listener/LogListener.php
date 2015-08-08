<?php
namespace Kemer\Ssdp\Listener;

use Kemer\Ssdp\SsdpEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogListener implements EventSubscriberInterface
{
    /**
     * CLI foreground colors
     */
     private $fColors = [
         'black' => '0;30',
         'dark_gray' => '1;30',
         'blue' => '0;34',
         'light_blue' => '1;34',
         'green' => '0;32',
         'light_green' => '1;32',
         'cyan' => '0;36',
         'light_cyan' => '1;36',
         'red' => '0;31',
         'light_red' => '1;31',
         'purple' => '0;35',
         'light_purple' => '1;35',
         'brown' => '0;33',
         'yellow' => '1;33',
         'light_gray' => '0;37',
         'white' => '1;37',
     ];

     /**
      * CLI background colors
      */
     private $bColors = [
        'transparent' => '',
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47',
     ];

    /**
     * Display event request
     *
     * @param SsdpEvent $event
     * @return void
     */
    public function onSsdpRequest(SsdpEvent $event)
    {
        $request = $event->getRequest();
        $this->display("#---- {$request->getMethod()} request ----", "light_purple");
        $this->display($request->toString(), "purple");
    }

    /**
     * Display event response
     *
     * @param SsdpEvent $event
     * @return void
     */
    public function onSsdpResponse(SsdpEvent $event)
    {
        //e("#----Send data to $ip:$port ----", "light_green");
        $this->display("#---- {$event->getRequest()->getMethod()} response ({$event->getSender()}) ----", "light_green");
        $this->display($event->getResponse(), "green");
    }

    /**
     * Echo colored string
     *
     * @param string $string
     * @param string $foreground
     * @param string $background
     * @return void
     */
    private function display($string, $foreground = null, $background = null)
    {
        echo $this->colour($string, $foreground, $background)."\n";
    }

    /**
     * Returns colored string
     *
     * @param string $string
     * @param string $foreground
     * @param string $background
     * @return string
     */
     private function colour($string, $foreground = null, $background = null)
     {
        return sprintf(
            $background ? "\033[%sm\033[%sm%s\033[0m" : "\033[%sm%s%s\033[0m",
            $this->fColors[isset($this->fColors[$foreground]) ? $foreground : 'black'],
            $this->bColors[isset($this->bColors[$background]) ? $background : 'transparent'],
            $string
            );
     }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SsdpEvent::RESPONSE => 'onSsdpResponse',
            SsdpEvent::SEARCH => 'onSsdpRequest',
            SsdpEvent::NOTIFY => 'onSsdpRequest',
        ];
    }
}
