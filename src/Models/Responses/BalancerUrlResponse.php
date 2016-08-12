<?php

namespace Nikapps\OrtcPhp\Models\Responses;

class BalancerUrlResponse extends OrtcResponse
{
    /**
     * @var string
     */
    private $url;

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
