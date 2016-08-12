<?php

namespace Nikapps\OrtcPhp;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\FutureResponse;
use GuzzleHttp\Pool;
use Nikapps\OrtcPhp\Exceptions\BatchRequestException;
use Nikapps\OrtcPhp\Exceptions\InvalidBalancerUrlException;
use Nikapps\OrtcPhp\Exceptions\NetworkErrorException;
use Nikapps\OrtcPhp\Exceptions\UnauthorizedException;
use Nikapps\OrtcPhp\Models\Requests\OrtcRequest;

class OrtcClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzleClient;

    /**
     * @var OrtcRequest
     */
    protected $request;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * execute single request.
     *
     * @throws UnauthorizedException
     * @throws NetworkErrorException
     * @throws InvalidBalancerUrlException
     *
     * @return Models\Responses\OrtcResponse
     */
    public function execute()
    {
        $response = null;

        try {
            $guzzleRequest = $this->createRequest();

            /** @var FutureResponse $response */
            $response = $this->guzzleClient->send($guzzleRequest);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                throw new UnauthorizedException();
            } else {
                $networkErrorException = new NetworkErrorException();
                $networkErrorException->setGuzzleClientException($e);

                throw $networkErrorException;
            }
        }

        $handler = $this->request->getResponseHandler();

        return $handler->handle($response);
    }

    /**
     * execute batch requests (post).
     *
     * @throws BatchRequestException
     *
     * @return Models\Responses\OrtcResponse
     */
    public function batchExecute()
    {
        $guzzleRequests = $this->createBatchPostRequests();

        $results = Pool::batch($this->guzzleClient, $guzzleRequests, [
            'pool_size' => $this->request->getOrtcConfig()->getBatchPoolSize(),
        ]);

        if (count($results->getFailures()) > 0) {
            $batchRequestException = new BatchRequestException();
            $batchRequestException->setResults($results);

            throw $batchRequestException;
        }

        $handler = $this->request->getResponseHandler();

        return $handler->handle($results);
    }

    /**
     * @param \GuzzleHttp\Client $guzzleClient
     */
    public function setGuzzleClient($guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param OrtcRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * get request options for post requests.
     *
     * @param array $postData
     *
     * @return array
     */
    protected function getRequestOptionsForPost($postData)
    {
        return [
            'verify' => $this->request->getOrtcConfig()->isVerifySsl(),
            'body'   => $postData,
        ];
    }

    /**
     * get request options for get requests.
     *
     * @return array
     */
    protected function getRequestOptionsForGet()
    {
        return [
            'verify' => $this->request->getOrtcConfig()->isVerifySsl(),
        ];
    }

    /**
     * create guzzle GET/POST request.
     *
     * @return \GuzzleHttp\Message\Request
     */
    protected function createRequest()
    {
        if ($this->request->isPost()) {
            return $this->guzzleClient->createRequest(
                'POST',
                $this->getRequestUrl(),
                $this->getRequestOptionsForPost($this->request->getPostData())
            );
        } else {
            return $this->guzzleClient->createRequest(
                'GET',
                $this->getRequestUrl(),
                $this->getRequestOptionsForGet()
            );
        }
    }

    /**
     * create batch guzzle POST requests.
     *
     * @return \GuzzleHttp\Message\Request[]
     */
    protected function createBatchPostRequests()
    {
        $requests = [];

        foreach ($this->request->getPostData() as $postData) {
            $requests[] = $this->guzzleClient->createRequest(
                'POST',
                $this->getRequestUrl(),
                $this->getRequestOptionsForPost($postData)
            );
        }

        return $requests;
    }

    /**
     * combine path url & baseUrl if needed!
     */
    protected function getRequestUrl()
    {
        if (!$this->request->isUrlAbsolute()) {
            return $this->baseUrl.$this->request->getUrlPath();
        } else {
            return $this->request->getUrlPath();
        }
    }
}
