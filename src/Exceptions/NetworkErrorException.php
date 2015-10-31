<?php
namespace Nikapps\Ortc\Exceptions;

use GuzzleHttp\Exception\ClientException;

class NetworkErrorException extends OrtcException
{
    /**
     * @var ClientException
     */
    protected $guzzleException;

    /**
     * @return ClientException
     */
    public function getGuzzleException()
    {
        return $this->guzzleException;
    }

    /**
     * @param ClientException $guzzleException
     * @return $this
     */
    public function setGuzzleException($guzzleException)
    {
        $this->guzzleException = $guzzleException;

        return $this;
    }
}