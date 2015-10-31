<?php
namespace Nikapps\Ortc;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Nikapps\Ortc\Config\Config;
use Nikapps\Ortc\Exceptions\UnauthorizedException;
use Nikapps\Ortc\Exceptions\InvalidServerUrlException;
use Nikapps\Ortc\Exceptions\NetworkErrorException;
use Nikapps\Ortc\Requests\Authenticate;

class Ortc
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Config
     */
    private $config;

    /**
     * Base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Ortc constructor.
     * @param Config $config
     * @param Client $client
     */
    public function __construct(Config $config = null, Client $client = null)
    {
        $this->config = is_null($config) ? $this->makeDefaultConfig() : $config;
        $this->client = is_null($client) ? $this->makeDefaultClient() : $client;
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
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Make default guzzle client
     *
     * @return Client
     */
    protected function makeDefaultClient()
    {
        return new Client();
    }

    /**
     * Make default config
     *
     * @return Config
     */
    private function makeDefaultConfig()
    {
        return new Config();
    }

    /**
     *
     *
     * @return string
     * @throws InvalidServerUrlException
     */
    public function fetchServer()
    {
        $response = $this->client->get($this->config->getServerUrl());
        $contents = $response->getBody()->getContents();

        if (preg_match('/var.*?"(.*?)"/', $contents, $matches)) {
            return $this->baseUrl = $matches[1];
        }

        throw (new InvalidServerUrlException())->setBody($contents);
    }

    /**
     * Authenticate an user token
     *
     * @param Authenticate $authenticate
     * @return bool
     * @throws NetworkErrorException
     * @throws UnauthorizedException
     */
    public function authenticate(Authenticate $authenticate)
    {
        $this->prepare();

        try {
            $this->client->post($this->config->getAuthenticationEndpoint(), [
                'base_uri' => $this->baseUrl,
                'form_params' => array_merge($authenticate->toArray(), $this->getCredentials())
            ]);

            return true;

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 401) {
                throw new UnauthorizedException;
            }

            throw (new NetworkErrorException($e->getMessage()))
                ->setGuzzleException($e);
        }
    }

    /**
     * Get api credentials
     *
     * @return array
     */
    protected function getCredentials()
    {
        return [
            'AK' => $this->config->getApplicationKey(),
            'PK' => $this->config->getPrivateKey()
        ];
    }

    /**
     * Prepare before sending a request
     *
     * @throws InvalidServerUrlException
     */
    protected function prepare()
    {
        if (is_null($this->baseUrl)) {
            $this->fetchServer();
        }
    }
}