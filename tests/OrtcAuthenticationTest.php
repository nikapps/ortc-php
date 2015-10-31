<?php
namespace Nikapps\Ortc\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Nikapps\Ortc\Config\Config;
use Nikapps\Ortc\Ortc;
use Nikapps\Ortc\Requests\Authenticate;

class OrtcAuthenticationTest extends OrtcTest
{

    /**
     * @test
     */
    function it_should_authenticate_and_request_correctly()
    {

        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('', 201)
            ],
            $container
        );

        $config = new Config('app-key', 'private-key');
        $ortc = new Ortc($config, $client);
        $ortc->setBaseUrl('http://server.com');

        $authentication = $this->createAuthenticateRequest();

        $this->assertTrue($ortc->authenticate($authentication));

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            'http://server.com/authenticate',
            $request->getUri()->__toString()
        );

        parse_str($request->getBody()->getContents(), $body);

        $this->assertArraySubset(
            array_merge(
                $authentication->toArray(),
                [
                    'PK' => 'private-key',
                    'AK' => 'app-key'
                ]
            ),
            $body
        );

    }

    /**
     * @test
     */
    function it_should_fetch_server_url_when_base_url_is_not_exist()
    {
        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('var SOCKET_SERVER = "http://api.server.com";'),
                $this->makeResponse('', 201)
            ],
            $container
        );

        $config = (new Config())
            ->serverUrl('http://server.com');
        $ortc = new Ortc($config, $client);

        $authentication = $this->createAuthenticateRequest();

        $this->assertTrue($ortc->authenticate($authentication));

        /** @var Request $serverRequest */
        $serverRequest = $container[0]['request'];
        /** @var Request $authenticationRequest */
        $authenticationRequest = $container[1]['request'];

        $this->assertEquals('GET', $serverRequest->getMethod());
        $this->assertEquals(
            'http://server.com',
            $serverRequest->getUri()->__toString()
        );

        $this->assertEquals('POST', $authenticationRequest->getMethod());
        $this->assertEquals(
            'http://api.server.com/authenticate',
            $authenticationRequest->getUri()->__toString()
        );


    }

    /**
     * @test
     * @expectedException \Nikapps\Ortc\Exceptions\UnauthorizedException
     */
    function it_should_throw_an_exception_when_credentials_are_invalid()
    {

        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('Unauthorized access', 401)
            ],
            $container
        );

        $ortc = new Ortc(null, $client);
        $ortc->setBaseUrl('http://server.com');
        $ortc->authenticate($this->createAuthenticateRequest());
    }

    /**
     * @return Authenticate
     */
    private function createAuthenticateRequest()
    {
        return (new Authenticate())
            ->addChannel('blue', 'r')
            ->addChannel('yellow', 'w')
            ->isPrivate()
            ->token('token')
            ->ttl(1800);
    }


}