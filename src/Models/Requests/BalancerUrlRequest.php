<?php

namespace Nikapps\OrtcPhp\Models\Requests;

use Nikapps\OrtcPhp\Handlers\BalancerUrlResponseHandler;
use Nikapps\OrtcPhp\Handlers\OrtcResponseHandler;

class BalancerUrlRequest extends OrtcRequest
{
    /**
     * get url path (not base url).
     *
     * @return string
     */
    public function getUrlPath()
    {
        $pathUrl = strtr(
            $this->getOrtcConfig()->getBalancerUrl(),
            [
                '{APP_KEY}' => $this->getOrtcConfig()->getApplicationKey(),
            ]
        );

        return $pathUrl;
    }

    /**
     * is post request or get request?
     *
     * @return bool
     */
    public function isPost()
    {
        return false;
    }

    /**
     * get post body.
     *
     * @return array
     */
    public function getPostData()
    {
        return [];
    }

    /**
     * get response handler.
     *
     * @return OrtcResponseHandler
     */
    public function getResponseHandler()
    {
        return new BalancerUrlResponseHandler();
    }

    public function isUrlAbsolute()
    {
        return true;
    }
}
