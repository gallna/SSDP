<?php
namespace Kemer\Ssdp\Notify;

use Kemer\Ssdp\SsdpMessage;

class ByebyeRequest extends AbstractNotifyRequest
{
    public function __construct()
    {
        $this->setNts('ssdp:byebye');
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
            ->setNt($message->getHeader("NT")->getFieldValue())
            ->setUsn($message->getHeader("USN")->getFieldValue());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $message = new SsdpMessage();
        $message->setMethod(SsdpMessage::METHOD_NOTIFY)
            ->setUri('*')
            ->getHeaders()->addHeaders([
                "HOST" => $this->getHost(),
                "NT" => $this->getNt(),
                "USN" => $this->getUsn()
            ]);
        return $message->toString();
    }
}
