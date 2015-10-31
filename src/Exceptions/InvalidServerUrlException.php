<?php
namespace Nikapps\Ortc\Exceptions;

class InvalidServerUrlException extends OrtcException
{
    /**
     * Exception message
     *
     * @var string
     */
    protected $message = "Cannot find the server url.";

    /**
     * Response body
     *
     * @var string
     */
    protected $body;

    /**
     * Get response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set response body
     *
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}