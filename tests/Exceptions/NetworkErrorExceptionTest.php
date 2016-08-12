<?php

namespace Tests\Exceptions;

use Mockery as m;
use Nikapps\OrtcPhp\Exceptions\NetworkErrorException;
use Tests\TestCase;

class NetworkErrorExceptionTest extends TestCase
{
    public function testImplements()
    {
        $exception = new NetworkErrorException();
        $this->assertEquals('Error in network connection', $exception->getMessage());

        $this->assertAttributeEquals(null, 'guzzleClientException', $exception);
        $this->assertEquals(null, $exception->getGuzzleClientException());

        $clientException = m::mock('GuzzleHttp\Exception\ClientException');
        $exception->setGuzzleClientException($clientException);
        $this->assertAttributeEquals($clientException, 'guzzleClientException', $exception);
        $this->assertEquals($clientException, $exception->getGuzzleClientException());
    }
}
