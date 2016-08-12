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
     * @var OrtcConfig
     */
    private $ortcClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * Ortc constructor.
     *
     * @param OrtcConfig $ortcConfig
     * @param OrtcClient $ortcClient
     */
    public function __construct(OrtcConfig $ortcConfig, OrtcClient $ortcClient)
    {
        $this->ortcConfig = $ortcConfig;
        $this->ortcClient = $ortcClient;
    }

    /**
     * Return Base Url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set Base Url
     *
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Return GuzzleHttp Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getGuzzleClient()
    {
        return $this->ortcClient->getGuzzleClient();
    }

    /**
     * prepare client before requesting.
     *
     * @return void
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

        $this->ortcClient->setRequest($balancerUrlRequest);

        return $this->ortcClient->execute();
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

        $this->ortcClient->setRequest($authRequest);
        $this->ortcClient->setBaseUrl($this->baseUrl);

        return $this->ortcClient->execute();
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

        $this->ortcClient->setRequest($sendMessageRequest);
        $this->ortcClient->setBaseUrl($this->baseUrl);

        return $this->ortcClient->batchExecute();
    }
}
