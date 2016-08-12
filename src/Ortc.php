<?php

namespace Nikapps\OrtcPhp;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Requests\AuthRequest;
use Nikapps\OrtcPhp\Models\Requests\BalancerUrlRequest;
use Nikapps\OrtcPhp\Models\Requests\SendMessageRequest;

class Ortc
{
    /**
     * @var OrtcConfig
     */
    private $ortcConfig;

    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Ortc constructor.
     *
     * @param OrtcConfig $ortcConfig
     */
    public function __construct(OrtcConfig $ortcConfig)
    {
        $this->ortcConfig = $ortcConfig;
        $this->guzzleClient = new \GuzzleHttp\Client();
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * @param \GuzzleHttp\Client $guzzleClient
     */
    public function setGuzzleClient($guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * prepare client before requesting.
     */
    protected function prepare()
    {
        if (!$this->baseUrl) {
            $balancerUrlResponse = $this->getBalancerUrl();
            $this->baseUrl = $balancerUrlResponse->getUrl();
        }
    }

    /**
     * get balancer url.
     *
     * @throws Exceptions\NetworkErrorException
     * @throws Exceptions\UnauthorizedException
     * @throws Exceptions\InvalidBalancerUrlException
     *
     * @return Models\Responses\BalancerUrlResponse
     */
    public function getBalancerUrl()
    {
        $this->baseUrl = null;

        $balancerUrlRequest = new BalancerUrlRequest();
        $balancerUrlRequest->setOrtcConfig($this->ortcConfig);

        $ortcClient = new OrtcClient();
        $ortcClient->setRequest($balancerUrlRequest);
        $ortcClient->setGuzzleClient($this->guzzleClient);

        return $ortcClient->execute();
    }

    /**
     * authenticate user.
     *
     * @param AuthRequest $authRequest
     *
     * @throws Exceptions\NetworkErrorException
     * @throws Exceptions\UnauthorizedException
     *
     * @return Models\Responses\AuthResponse
     */
    public function authenticate(AuthRequest $authRequest)
    {
        $this->prepare();

        $authRequest->setOrtcConfig($this->ortcConfig);

        $ortcClient = new OrtcClient();
        $ortcClient->setRequest($authRequest);
        $ortcClient->setGuzzleClient($this->guzzleClient);
        $ortcClient->setBaseUrl($this->baseUrl);

        return $ortcClient->execute();
    }

    /**
     * send message (push).
     *
     * @param SendMessageRequest $sendMessageRequest
     *
     * @throws Exceptions\BatchRequestException
     *
     * @return Models\Responses\SendMessageResponse
     */
    public function sendMessage(SendMessageRequest $sendMessageRequest)
    {
        $this->prepare();

        $sendMessageRequest->setOrtcConfig($this->ortcConfig);

        $ortcClient = new OrtcClient();
        $ortcClient->setRequest($sendMessageRequest);
        $ortcClient->setGuzzleClient($this->guzzleClient);
        $ortcClient->setBaseUrl($this->baseUrl);

        return $ortcClient->batchExecute();
    }
}
