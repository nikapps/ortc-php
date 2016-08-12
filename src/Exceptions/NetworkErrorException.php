<?php

namespace Nikapps\OrtcPhp\Exceptions;

use GuzzleHttp\Exception\ClientException;

class NetworkErrorException extends OrtcException
{
    protected $message = 'Error in network connection';

    /**
     * @var ClientException
     */
    private $guzzleClientException;

    /**
     * @return ClientException
     */
    public function getGuzzleClientException()
    {
        return $this->guzzleClientException;
    }

    /**
     * @param ClientException $guzzleClientException
     */
    public function setGuzzleClientException(ClientException $guzzleClientException)
    {
        $this->guzzleClientException = $guzzleClientException;
    }
}
