<?php
namespace Kemer\Ssdp;

use Zend\Stdlib\RequestInterface;
use Zend\Http\AbstractMessage;
use Zend\Http\Exception;
use Zend\Http\Headers;

/**
 * SSDP Message
 *
 * @link http://www.w3.org/Protocols/rfc2616/rfc2616.html
 */
class SsdpMessage extends AbstractMessage implements RequestInterface
{
    /**#@+
     * @const string METHOD constant names
     */
    const METHOD_NOTIFY = 'NOTIFY';
    const METHOD_SEARCH = 'M-SEARCH';
    /**#@-*/

    /**
     * @var string
     */
    protected $method = self::METHOD_SEARCH;

    /**
     * @var string
     */
    protected $uri = null;

    /**
     * A factory that produces a Request object from a well-formed Ssdp request string
     *
     * @param  string $string
     * @throws Exception\InvalidArgumentException
     * @return Request
     */
    public static function fromString($string)
    {
        $request = new static();

        $lines = explode("\r\n", $string);

        // first line must be Method/Uri/Version string
        $matches   = null;
        $methods   = implode(
                '|',
                array(
                    self::METHOD_NOTIFY,
                    self::METHOD_SEARCH
                )
            );

        $regex = '#^(?P<method>' . $methods . ')\s(?P<uri>[^ ]*)(?:\sHTTP\/(?P<version>\d+\.\d+)){0,1}#';
        $firstLine = array_shift($lines);
        if (!preg_match($regex, $firstLine, $matches)) {
            throw new Exception\InvalidArgumentException(
                'A valid request line was not found in the provided string'
            );
        }

        $request->setMethod($matches['method']);
        $request->setUri($matches['uri']);

        if (isset($matches['version'])) {
            $request->setVersion($matches['version']);
        }

        if (count($lines) == 0) {
            return $request;
        }

        $isHeader = true;
        $headers = $rawBody = array();
        while ($lines) {
            $nextLine = array_shift($lines);
            if ($nextLine == '') {
                $isHeader = false;
                continue;
            }

            if ($isHeader) {
                if (preg_match("/[\r\n]/", $nextLine)) {
                    throw new Exception\RuntimeException('CRLF injection detected');
                }
                $headers[] = $nextLine;
                continue;
            }

            if (empty($rawBody)
                && preg_match('/^[a-z0-9!#$%&\'*+.^_`|~-]+:$/i', $nextLine)
            ) {
                throw new Exception\RuntimeException('CRLF injection detected');
            }

            $rawBody[] = $nextLine;
        }

        if ($headers) {
            $request->headers = implode("\r\n", $headers);
        }

        return $request;
    }

    /**
     * Set the method for this request
     *
     * @param  string $method
     * @return Request
     * @throws Exception\InvalidArgumentException
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Return the method for this request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the URI/URL for this request.
     *
     * @param string $uri
     * @return Request
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Return the URI for this request object as a string
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Return the header container responsible for headers or all headers of a certain name/type
     *
     * @see \Zend\Http\Headers::get()
     * @param string|null           $name            Header name to retrieve, or null to get the whole container.
     * @param mixed|null            $default         Default value to use when the requested header is missing.
     * @return \Zend\Http\Headers|bool|\Zend\Http\Header\HeaderInterface|\ArrayIterator
     */
    public function getHeaders($name = null, $default = false)
    {
        if ($this->headers === null || is_string($this->headers)) {
            // this is only here for fromString lazy loading
            $this->headers = (is_string($this->headers)) ? Headers::fromString($this->headers) : new Headers();
        }

        if ($name === null) {
            return $this->headers;
        }

        if ($this->headers->has($name)) {
            return $this->headers->get($name);
        }

        return $default;
    }

    /**
     * Get all headers of a certain name/type.
     *
     * @see Request::getHeaders()
     * @param string|null           $name            Header name to retrieve, or null to get the whole container.
     * @param mixed|null            $default         Default value to use when the requested header is missing.
     * @return \Zend\Http\Headers|bool|\Zend\Http\Header\HeaderInterface|\ArrayIterator
     */
    public function getHeader($name, $default = false)
    {
        return $this->getHeaders($name, $default);
    }

    /**
     * Return the formatted request line (first line) for this http request
     *
     * @return string
     */
    public function renderRequestLine()
    {
        return $this->method . ' ' . (string) $this->uri . ' HTTP/' . $this->version;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $str = $this->renderRequestLine() . "\r\n";
        $str .= $this->getHeaders()->toString();
        $str .= "\r\n";
        $str .= $this->getContent();
        return $str;
    }
}
