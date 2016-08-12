<?php

namespace Nikapps\OrtcPhp\Models\Requests;

use Nikapps\OrtcPhp\Handlers\AuthResponseHandler;
use Nikapps\OrtcPhp\Handlers\OrtcResponseHandler;
use Nikapps\OrtcPhp\Models\Channel;

class AuthRequest extends OrtcRequest
{
    /**
     * channels.
     *
     * @var Channel[]
     */
    private $channels;

    /**
     * authentication token.
     *
     * @var string
     */
    private $authToken;

    /**
     * expiration time (ttl) in seconds.
     *
     * @var int
     */
    private $expireTime;

    /**
     * @var bool
     */
    private $private = false;

    /**
     * get url path (not base url).
     *
     * @return string
     */
    public function getUrlPath()
    {
        return $this->getOrtcConfig()->getAuthenticationPath();
    }

    /**
     * is post request or get request?
     *
     * @return bool
     */
    public function isPost()
    {
        return true;
    }

    /**
     * get post body.
     *
     * @return array
     */
    public function getPostData()
    {
        $postData = [
            'AT'  => $this->getAuthToken(),
            'PVT' => intval($this->isPrivate()),
            'AK'  => $this->getOrtcConfig()->getApplicationKey(),
            'TTL' => $this->getExpireTime(),
            'PK'  => $this->getOrtcConfig()->getPrivateKey(),
            'TP'  => count($this->getChannels()),
        ];

        foreach ($this->getChannels() as $channel) {
            $postData[$channel->getName()] = $channel->getPermission();
        }

        return $postData;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     *
     * @return $this
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * @return \Nikapps\OrtcPhp\Models\Channel[]
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param \Nikapps\OrtcPhp\Models\Channel[] $channels
     *
     * @return $this
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param int $expireTime
     *
     * @return $this
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param bool $private
     *
     * @return $this
     */
    public function setPrivate($private)
    {
        $this->private = (bool) $private;

        return $this;
    }

    /**
     * get response handler.
     *
     * @return OrtcResponseHandler
     */
    public function getResponseHandler()
    {
        return new AuthResponseHandler();
    }
}
