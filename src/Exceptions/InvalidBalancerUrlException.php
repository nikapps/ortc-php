<?php

namespace Nikapps\OrtcPhp\Exceptions;

class InvalidBalancerUrlException extends OrtcException
{
    protected $message = 'Balancer URL is invalid';

    /**
     * @var string
     */
    protected $url;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
