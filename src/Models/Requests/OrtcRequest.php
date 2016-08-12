<?php

namespace Nikapps\OrtcPhp\Models\Requests;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Handlers\OrtcResponseHandler;

abstract class OrtcRequest
{
    /**
     * @var OrtcConfig
     */
    private $ortcConfig;

    /**
     * get url path (not base url).
     *
     * @return string
     */
    abstract public function getUrlPath();

    /**
     * is post request or get request?
     *
     * @return bool
     */
    abstract public function isPost();

    /**
     * get post body.
     *
     * @return array
     */
    abstract public function getPostData();

    /**
     * get response handler.
     *
     * @return OrtcResponseHandler
     */
    abstract public function getResponseHandler();

    /**
     * @return OrtcConfig
     */
    public function getOrtcConfig()
    {
        return $this->ortcConfig;
    }

    /**
     * @param OrtcConfig $ortcConfig
     *
     * @return $this
     */
    public function setOrtcConfig($ortcConfig)
    {
        $this->ortcConfig = $ortcConfig;

        return $this;
    }

    /**
     * is absolute url?
     *
     * @return bool
     */
    public function isUrlAbsolute()
    {
        return false;
    }
}
