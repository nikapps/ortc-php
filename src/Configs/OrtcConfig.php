<?php

namespace Nikapps\OrtcPhp\Configs;

class OrtcConfig
{
    /**
     * Realtime.co balancer url.
     *
     * @var string
     */
    protected $balancerUrl = 'https://ortc-developers.realtime.co/server/2.1?appkey={APP_KEY}';

    /**
     * Your realtime.co application key.
     *
     * @var string
     */
    protected $applicationKey;

    /**
     * Your realtime.co private key.
     *
     * @var string
     */
    protected $privateKey;

    /**
     * authentication path.
     *
     * @var string
     */
    protected $authenticationPath = '/authenticate';

    /**
     * send push message to channel(s) path.
     *
     * @var string
     */
    protected $sendPath = '/send';

    /**
     * maximum size of message chunk in bytes.
     *
     * @var int
     */
    protected $maxChunkSize = 700;

    /**
     * maximum size of message chunk in bytes.
     *
     * @var int
     */
    protected $batchPoolSize = 5;

    /**
     * pre concatenating string for every message chunks.
     *
     * @var string
     */
    protected $preMessageString = '{RANDOM}_{PART}-{TOTAL_PARTS}_';

    /**
     * verify ssl/tls certificate.
     *
     * @var bool
     */
    protected $verifySsl = true;

    /**
     * @return string
     */
    public function getApplicationKey()
    {
        return $this->applicationKey;
    }

    /**
     * @param string $applicationKey
     */
    public function setApplicationKey($applicationKey)
    {
        $this->applicationKey = $applicationKey;
    }

    /**
     * @return string
     */
    public function getBalancerUrl()
    {
        return $this->balancerUrl;
    }

    /**
     * @param string $balancerUrl
     */
    public function setBalancerUrl($balancerUrl)
    {
        $this->balancerUrl = $balancerUrl;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getAuthenticationPath()
    {
        return $this->authenticationPath;
    }

    /**
     * @param string $authenticationPath
     */
    public function setAuthenticationPath($authenticationPath)
    {
        $this->authenticationPath = $authenticationPath;
    }

    /**
     * @return string
     */
    public function getSendPath()
    {
        return $this->sendPath;
    }

    /**
     * @param string $sendPath
     */
    public function setSendPath($sendPath)
    {
        $this->sendPath = $sendPath;
    }

    /**
     * @return int
     */
    public function getMaxChunkSize()
    {
        return $this->maxChunkSize;
    }

    /**
     * @param int $maxChunkSize
     */
    public function setMaxChunkSize($maxChunkSize)
    {
        $this->maxChunkSize = $maxChunkSize;
    }

    /**
     * @return string
     */
    public function getPreMessageString()
    {
        return $this->preMessageString;
    }

    /**
     * @param string $preMessageString
     */
    public function setPreMessageString($preMessageString)
    {
        $this->preMessageString = $preMessageString;
    }

    /**
     * @return bool
     */
    public function isVerifySsl()
    {
        return $this->verifySsl;
    }

    /**
     * @param bool $verifySsl
     */
    public function setVerifySsl($verifySsl)
    {
        $this->verifySsl = (bool) $verifySsl;
    }

    /**
     * @return int
     */
    public function getBatchPoolSize()
    {
        return $this->batchPoolSize;
    }

    /**
     * @param int $batchPoolSize
     */
    public function setBatchPoolSize($batchPoolSize)
    {
        $this->batchPoolSize = $batchPoolSize;
    }
}
