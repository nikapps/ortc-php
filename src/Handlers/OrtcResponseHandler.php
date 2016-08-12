<?php

namespace Nikapps\OrtcPhp\Handlers;

use GuzzleHttp\BatchResults;
use GuzzleHttp\Message\FutureResponse;
use Nikapps\OrtcPhp\Models\Responses\OrtcResponse;

abstract class OrtcResponseHandler
{
    /**
     * handle response from guzzle.
     *
     * @param FutureResponse|BatchResults $response
     *
     * @return OrtcResponse
     */
    abstract public function handle($response);
}
