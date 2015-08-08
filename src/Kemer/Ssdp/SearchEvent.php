<?php
namespace Kemer\Ssdp;

use Kemer\Ssdp\Search\SearchRequestInterface;
use Kemer\Ssdp\Search\SearchResponseInterface;

class SearchEvent extends SsdpEvent
{
    const ALL = 'ssdp:all';
    const ROOTDEVICE = 'upnp:rootdevice';

    /**
     * @var SearchRequestInterface
     */
    protected $searchRequest;

    /**
     * @var SearchResponseInterface
     */
    protected $searchResponses = [];

    /**
     * Get request
     *
     * @return SearchRequestInterface
     */
    public function getSearchRequest()
    {
        return $this->searchRequest;
    }

    /**
     * Get request
     *
     * @param SearchRequestInterface $request
     * @return $this
     */
    public function setSearchRequest(SearchRequestInterface $request)
    {
        $this->searchRequest = $request;
        return $this;
    }

    /**
     * Get response
     *
     * @return []SearchResponseInterface
     */
    public function getSearchResponses()
    {
        return $this->searchResponses;
    }

    /**
     * Add search response
     *
     * @param SearchResponseInterface $response
     * @return $this
     */
    public function addSearchResponse(SearchResponseInterface $response)
    {
        $this->searchResponses[] = $response;
        return $this;
    }
}
