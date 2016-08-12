<?php

namespace Nikapps\OrtcPhp\Handlers;

use GuzzleHttp\Message\FutureResponse;
use Nikapps\OrtcPhp\Exceptions\InvalidBalancerUrlException;
use Nikapps\OrtcPhp\Models\Responses\BalancerUrlResponse;

class BalancerUrlResponseHandler extends OrtcResponseHandler
{
    /**
     * handle response from guzzle.
     *
     * @param FutureResponse $response
     *
     * @return BalancerUrlResponse
     */
    public function handle($response)
    {
        $body = trim((string) $response);

        $url = $this->parseUrl($body);
        $this->validate($url);

        $balancerUrlResponse = new BalancerUrlResponse();
        $balancerUrlResponse->setUrl($url);

        return $balancerUrlResponse;
    }

    /**
     * validating response.
     *
     * @param string $url
     *
     * @throws InvalidBalancerUrlException
     */
    public function validate($url)
    {
        if (preg_match('/https?:\/\/undefined(:undefined)?/', $url)) {
            $invalidBalancerUrlException = new InvalidBalancerUrlException();
            $invalidBalancerUrlException->setUrl($url);

            throw $invalidBalancerUrlException;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $invalidBalancerUrlException = new InvalidBalancerUrlException();
            $invalidBalancerUrlException->setUrl($url);

            throw $invalidBalancerUrlException;
        }
    }

    /**
     * parse body to find url.
     *
     * @param string $body
     *
     * @throws InvalidBalancerUrlException
     *
     * @return string
     */
    public function parseUrl($body)
    {
        if (!preg_match('/https?:\/\/[^\"]+/', $body, $matches)) {
            $invalidBalancerUrlException = new InvalidBalancerUrlException();
            $invalidBalancerUrlException->setUrl($body);

            throw $invalidBalancerUrlException;
        }

        return trim($matches[0]);
    }
}
