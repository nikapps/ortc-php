<?php
namespace Nikapps\Ortc\Config;

class Config
{
    /**
     * Endpoint for fetching best server
     *
     * @var string
     */
    private $serverUrl = 'https://ortc-developers.realtime.co/server/2.1?appkey=abcde1';

    private $endpoints = [
        'authentication' => '/authenticate',
        'send' => '/send'
    ];

    /**
     * Ortc application key
     *
     * @var string
     */
    private $applicationKey = 'your-application-key';

    /**
     * Ortc private key
     *
     * @var string
     */
    private $privateKey = 'your-private-key';

    /**
     * Config constructor.
     * @param string $applicationKey
     * @param string $privateKey
     */
    public function __construct($applicationKey = null, $privateKey = null)
    {
        $this->applicationKey = is_null($applicationKey) ?: $applicationKey;
        $this->privateKey = is_null($privateKey) ?: $privateKey;
    }


    /**
     * Get endpoint url for fetching best server
     *
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * Set endpoint url for fetching best server
     *
     * @param string $serverUrl
     * @return $this
     */
    public function serverUrl($serverUrl)
    {
        $this->serverUrl = $serverUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationKey()
    {
        return $this->applicationKey;
    }

    /**
     * @param string $applicationKey
     * @return $this
     */
    public function applicationKey($applicationKey)
    {
        $this->applicationKey = $applicationKey;

        return $this;
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
     * @return $this
     */
    public function privateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Set Authentication endpoint
     *
     * @param string $url
     * @return $this
     */
    public function authenticationEndpoint($url)
    {
        $this->endpoints['authentication'] = $url;

        return $this;
    }

    /**
     * Get authentication endpoint
     *
     * @return string
     */
    public function getAuthenticationEndpoint()
    {
        return $this->endpoints['authentication'];
    }

    /**
     * @return string
     */
    public function getSendEndpoint()
    {
        return $this->endpoints['send'];
    }

    /**
     * @param string $url
     * @return $this
     */
    public function sendEndpoint($url)
    {
        $this->endpoints['send'] = $url;

        return $this;
    }


}