<?php
namespace Tests;

use Mockery as m;
use Nikapps\OrtcPhp\OrtcClient;
use Nikapps\OrtcPhp\Configs\OrtcConfig;

class OrtcClientTest extends TestCase
{
    public function testImplementsAndSettersGetters()
    {
        $guzzleClient = m::mock('GuzzleHttp\Client');
        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);
        $ortcClient->setBaseUrl('http://base/local');

        $this->assertAttributeEquals($guzzleClient, 'guzzleClient', $ortcClient);        
        $this->assertAttributeEquals($ortcRequest, 'request', $ortcClient);
        $this->assertAttributeEquals('http://base/local', 'baseUrl', $ortcClient);
        $this->assertEquals($guzzleClient, $ortcClient->getGuzzleClient());
    }

    public function testExecuteShouldThrowUnauthorizedException()
    {
        $this->setExpectedException('Nikapps\OrtcPhp\Exceptions\UnauthorizedException');

        $responseException = m::mock('GuzzleHttp\Message\ResponseInterface');
        $responseException->shouldReceive('getStatusCode')->once()->andReturn(401);

        $exception = m::mock('GuzzleHttp\Exception\ClientException');
        $exception->shouldReceive('getResponse')->once()->andReturn($responseException);

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isPost')->once()->andThrow($exception);

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);

        $ortcClient->execute();
    }

    public function testExecuteShouldThrowNetworkErrorException()
    {
        $this->setExpectedException('Nikapps\OrtcPhp\Exceptions\NetworkErrorException');

        $responseException = m::mock('GuzzleHttp\Message\ResponseInterface');
        $responseException->shouldReceive('getStatusCode')->once()->andReturn(400);
        
        $exception = m::mock('GuzzleHttp\Exception\ClientException');
        $exception->shouldReceive('getResponse')->once()->andReturn($responseException);

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isPost')->once()->andThrow($exception);
        
        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);
        
        $ortcClient->execute();
    }

    public function testExecuteWithPostShouldReturnedResponseHandler()
    {
        $ortcConfig = new OrtcConfig;
        $ortcConfig->setVerifySsl(false);

        $ortcResponse = m::mock('OrtcResponse');
        $responseHandler = m::mock('OrtcResponseHandler');
        $responseHandler->shouldReceive('handle')->once()->with('{document: [response]}')->andReturn($ortcResponse);

        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isPost')->once()->andReturn(true);
        $ortcRequest->shouldReceive('isUrlAbsolute')->once()->andReturn(true);
        $ortcRequest->shouldReceive('getUrlPath')->once()->andReturn('https://local/auth');
        $ortcRequest->shouldReceive('getPostData')->once()->andReturn(['foo' => 'bar']);
        $ortcRequest->shouldReceive('getOrtcConfig')->once()->andReturn($ortcConfig);
        $ortcRequest->shouldReceive('getResponseHandler')->once()->andReturn($responseHandler);

        $guzzleRequest = m::mock('GuzzleHttp\Message\Request');

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $guzzleClient
            ->shouldReceive('createRequest')
            ->once()
            ->with('POST', 'https://local/auth', ['verify' => false, 'body' => ['foo' => 'bar']])
            ->andReturn($guzzleRequest);

        $guzzleClient
            ->shouldReceive('send')
            ->once()
            ->with($guzzleRequest)
            ->andReturn('{document: [response]}');

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);

        $this->assertEquals($ortcResponse, $ortcClient->execute());
    }

    public function testExecuteWithGetShouldReturnedResponseHandler()
    {
        $ortcConfig = new OrtcConfig;
        $ortcConfig->setVerifySsl(true);

        $ortcResponse = m::mock('OrtcResponse');
        $responseHandler = m::mock('OrtcResponseHandler');
        $responseHandler->shouldReceive('handle')->once()->with('{document: [response]}')->andReturn($ortcResponse);

        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isPost')->once()->andReturn(false);
        $ortcRequest->shouldReceive('isUrlAbsolute')->once()->andReturn(false);
        $ortcRequest->shouldReceive('getUrlPath')->once()->andReturn('http://localhost/authentication');
        $ortcRequest->shouldReceive('getPostData')->never();
        $ortcRequest->shouldReceive('getOrtcConfig')->once()->andReturn($ortcConfig);
        $ortcRequest->shouldReceive('getResponseHandler')->once()->andReturn($responseHandler);

        $guzzleRequest = m::mock('GuzzleHttp\Message\Request');

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $guzzleClient
            ->shouldReceive('createRequest')
            ->once()
            ->with('GET', 'http://localhost/authentication', ['verify' => true])
            ->andReturn($guzzleRequest);

        $guzzleClient
            ->shouldReceive('send')
            ->once()
            ->with($guzzleRequest)
            ->andReturn('{document: [response]}');

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);

        $this->assertEquals($ortcResponse, $ortcClient->execute());
    }

    public function testBatchExecuteShouldThrowBatchRequestException()
    {
        $this->setExpectedException('Nikapps\OrtcPhp\Exceptions\BatchRequestException');
        
        $ortcConfig = new OrtcConfig;
        $ortcConfig->setVerifySsl(false);

        $promise = m::mock('PromiseInterface');
        $promise->shouldReceive('then')->once();
        $futureResponse = m::mock('GuzzleHttp\Message\FutureResponse');
        $futureResponse->shouldReceive('promise')->once()->andReturn($promise);

        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isUrlAbsolute')->once()->andReturn(true);
        $ortcRequest->shouldReceive('getUrlPath')->once()->andReturn('https://local/auth');
        $ortcRequest->shouldReceive('getPostData')->once()->andReturn(['foo' => 'bar']);
        $ortcRequest->shouldReceive('getOrtcConfig')->twice()->andReturn($ortcConfig);
        $ortcRequest->shouldReceive('getResponseHandler')->never();

        $guzzleRequest = new \GuzzleHttp\Message\Request('POST', 'https://local/auth');

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $guzzleClient
            ->shouldReceive('createRequest')
            ->once()
            ->with('POST', 'https://local/auth', ['verify' => false, 'body' => 'bar'])
            ->andReturn($guzzleRequest);

        $guzzleClient
            ->shouldReceive('send')
            ->once()
            ->with($guzzleRequest)
            ->andReturnUsing(function() use ($guzzleClient, $guzzleRequest, $futureResponse){
                $trans = new \GuzzleHttp\Transaction($guzzleClient, $guzzleRequest);
                $trans->exception = new \Exception;
                $guzzleRequest->getEmitter()->emit('end', new \GuzzleHttp\Event\EndEvent($trans));
                return $futureResponse;
            });

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);

        $ortcClient->batchExecute();
    }

    public function testBatchExecute()
    {
        $ortcConfig = new OrtcConfig;
        $ortcConfig->setVerifySsl(false);

        $promise = m::mock('PromiseInterface');
        $promise->shouldReceive('then')->once();
        $futureResponse = m::mock('GuzzleHttp\Message\FutureResponse');
        $futureResponse->shouldReceive('promise')->once()->andReturn($promise);

        $ortcResponse = m::mock('OrtcResponse');
        $responseHandler = m::mock('OrtcResponseHandler');
        $responseHandler->shouldReceive('handle')->once()->andReturn($ortcResponse);

        $ortcRequest = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest');
        $ortcRequest->shouldReceive('isUrlAbsolute')->once()->andReturn(true);
        $ortcRequest->shouldReceive('getUrlPath')->once()->andReturn('https://local/auth');
        $ortcRequest->shouldReceive('getPostData')->once()->andReturn(['foo' => 'bar']);
        $ortcRequest->shouldReceive('getOrtcConfig')->twice()->andReturn($ortcConfig);
        $ortcRequest->shouldReceive('getResponseHandler')->once()->andReturn($responseHandler);

        $guzzleRequest = new \GuzzleHttp\Message\Request('POST', 'https://local/auth');

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $guzzleClient
            ->shouldReceive('createRequest')
            ->once()
            ->with('POST', 'https://local/auth', ['verify' => false, 'body' => 'bar'])
            ->andReturn($guzzleRequest);

        $guzzleClient
            ->shouldReceive('send')
            ->once()
            ->with($guzzleRequest)
            ->andReturn($futureResponse);

        $ortcClient = new OrtcClient($guzzleClient);
        $ortcClient->setRequest($ortcRequest);

        $this->assertEquals($ortcResponse, $ortcClient->batchExecute());
    }
}
