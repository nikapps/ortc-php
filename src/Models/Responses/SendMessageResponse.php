<?php

namespace Nikapps\OrtcPhp\Models\Responses;

use GuzzleHttp\BatchResults;

class SendMessageResponse extends OrtcResponse
{
    /**
     * @var BatchResults
     */
    private $results;

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
