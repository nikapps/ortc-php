<?php

namespace Nikapps\OrtcPhp\Exceptions;

class BatchRequestException extends OrtcException
{
    protected $message = 'At least one request is failed';

    /**
     * @var \GuzzleHttp\BatchResults
     */
    protected $results;

    /**
     * @return \GuzzleHttp\BatchResults
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param \GuzzleHttp\BatchResults $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
