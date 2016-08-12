<?php
namespace Tests;

use Mockery as m;
use Nikapps\OrtcPhp\Ortc;

class OrtcTest extends TestCase
{
    public function testImplementsAndSettersGetters()
    {
        $ortcConfig = m::mock('Nikapps\OrtcPhp\Configs\OrtcConfig');

        $guzzleClient = m::mock('GuzzleHttp\Client');
        $ortcClient = m::mock('Nikapps\OrtcPhp\OrtcClient');
        $ortcClient->shouldReceive('getGuzzleClient')->once()->andReturn($guzzleClient);

        $ortc = new Ortc($ortcConfig, $ortcClient);
        $ortc->setBaseUrl('https://localhost');

        $this->assertAttributeEquals($ortcConfig, 'ortcConfig', $ortc);
        $this->assertAttributeEquals($ortcClient, 'ortcClient', $ortc);
        $this->assertAttributeEquals('https://localhost', 'baseUrl', $ortc);
        $this->assertEquals('https://localhost', $ortc->getBaseUrl());
        $this->assertEquals($guzzleClient, $ortc->getGuzzleClient());
    }

    public function testGetBalancerUrl()
    {
        $ortcConfig = m::mock('Nikapps\OrtcPhp\Configs\OrtcConfig');

        $responseUrl = m::mock('Nikapps\OrtcPhp\Models\Responses\OrtcResponse');
        $ortcClient = $this->mockClientBalancerUrlRequest($responseUrl);

        $ortc = new Ortc($ortcConfig, $ortcClient);
        $ortc->setBaseUrl('https://localhost');

        $this->assertEquals($responseUrl, $ortc->getBalancerUrl());
        $this->assertAttributeEquals(null, 'baseUrl', $ortc);
    }

    public function testAuthenticateWithPrepare()
    {
        $responseUrl = m::mock('Nikapps\OrtcPhp\Models\Responses\OrtcResponse');
        $responseUrl->shouldReceive('getUrl')->once()->andReturn('https://new.url.com');
        $ortcClient = $this->mockClientBalancerUrlRequest($responseUrl);

        $ortcConfig = m::mock('Nikapps\OrtcPhp\Configs\OrtcConfig');
        $requestAuth = m::mock('Nikapps\OrtcPhp\Models\Requests\AuthRequest');
        $requestAuth->shouldReceive('setOrtcConfig')->once()->with($ortcConfig);

        $response = m::mock('Nikapps\OrtcPhp\Models\Responses\AuthResponse');
        $ortcClient->shouldReceive('setRequest')->once()->ordered()->with($requestAuth);
        $ortcClient->shouldReceive('setBaseUrl')->once()->ordered()->with('https://new.url.com');
        $ortcClient->shouldReceive('execute')->once()->ordered()->andReturn($response);
        
        $ortc = new Ortc($ortcConfig, $ortcClient);

        $this->assertEquals($response, $ortc->authenticate($requestAuth));
        $this->assertAttributeEquals('https://new.url.com', 'baseUrl', $ortc);
    }

    public function testAuthenticateWithoutPrepare()
    {
        $ortcConfig = m::mock('Nikapps\OrtcPhp\Configs\OrtcConfig');

        $requestAuth = m::mock('Nikapps\OrtcPhp\Models\Requests\AuthRequest');
        $requestAuth->shouldReceive('setOrtcConfig')->once()->with($ortcConfig);

        $response = m::mock('Nikapps\OrtcPhp\Models\Responses\AuthResponse');
        $ortcClient = m::mock('Nikapps\OrtcPhp\OrtcClient');
        $ortcClient->shouldReceive('setRequest')->once()->with($requestAuth);
        $ortcClient->shouldReceive('setBaseUrl')->once()->with('https://foo.bar');
        $ortcClient->shouldReceive('execute')->once()->andReturn($response);
        
        $ortc = new Ortc($ortcConfig, $ortcClient);
        $ortc->setBaseUrl('https://foo.bar');

        $this->assertEquals($response, $ortc->authenticate($requestAuth));
        $this->assertAttributeEquals('https://foo.bar', 'baseUrl', $ortc);
    }

    public function testSendMessageWithoutPrepare()
    {
        $ortcConfig = m::mock('Nikapps\OrtcPhp\Configs\OrtcConfig');

        $requestSendMessage = m::mock('Nikapps\OrtcPhp\Models\Requests\SendMessageRequest');
        $requestSendMessage->shouldReceive('setOrtcConfig')->once()->with($ortcConfig);

        $response = m::mock('Nikapps\OrtcPhp\Models\Responses\SendMessageResponse');
        $ortcClient = m::mock('Nikapps\OrtcPhp\OrtcClient');
        $ortcClient->shouldReceive('setRequest')->once()->with($requestSendMessage);
        $ortcClient->shouldReceive('setBaseUrl')->once()->with('https://foo.bar');
        $ortcClient->shouldReceive('batchExecute')->once()->andReturn($response);
        
        $ortc = new Ortc($ortcConfig, $ortcClient);
        $ortc->setBaseUrl('https://foo.bar');

        $this->assertEquals($response, $ortc->sendMessage($requestSendMessage));
        $this->assertAttributeEquals('https://foo.bar', 'baseUrl', $ortc);
    }

    private function mockClientBalancerUrlRequest($responseUrl)
    {
        $self = $this;

        $ortcClient = m::mock('Nikapps\OrtcPhp\OrtcClient');
        $ortcClient->shouldReceive('setRequest')
            ->once()
            ->ordered()
            ->andReturnUsing(function($request) use($self) {
                $self->assertInstanceOf('Nikapps\OrtcPhp\Models\Requests\BalancerUrlRequest', $request);
            });
        $ortcClient->shouldReceive('execute')->once()->ordered()->andReturn($responseUrl);

        return $ortcClient;
    }
}
