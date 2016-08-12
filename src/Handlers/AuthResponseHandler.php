<?php

namespace Nikapps\OrtcPhp\Handlers;

use GuzzleHttp\Message\FutureResponse;
use Nikapps\OrtcPhp\Models\Responses\AuthResponse;

class AuthResponseHandler extends OrtcResponseHandler
{
    /**
     * handle response from guzzle.
     *
     * @param FutureResponse $response
     *
     * @return AuthResponse
     */
    public function handle($response)
    {
        $authResponse = new AuthResponse();
        $authResponse->setFailed(false);

        return $authResponse;
    }
}
