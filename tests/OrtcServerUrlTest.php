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

class OrtcServerUrlTest extends OrtcTest
{

    /**
     * @test
     */
    function it_should_request_correctly()
    {

        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('var SOCKET_SERVER = "http://ortc-developers2-euwest1-s0001.realtime.co";')
            ],
            $container
        );

        $config = (new Config())
            ->serverUrl('http://server.com');

        $ortc = new Ortc($config, $client);

        $ortc->fetchServer();

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http://server.com', $request->getUri()->__toString());

    }

    /**
     * @test
     */
    function it_should_return_best_server_url()
    {

        $client = $this->makeClient(
            [
                $this->makeResponse('var SOCKET_SERVER = "http://ortc-developers2-euwest1-s0001.realtime.co";')
            ]
        );

        $ortc = new Ortc(null, $client);

        $url = $ortc->fetchServer();

        $this->assertEquals('http://ortc-developers2-euwest1-s0001.realtime.co', $url);

    }

    /**
     * @test
     * @expectedException \Nikapps\Ortc\Exceptions\InvalidServerUrlException
     */
    function it_should_throw_an_exception_when_it_cannot_find_server_url()
    {
        $client = $this->makeClient(
            [
                $this->makeResponse('There is no url for you!')
            ]
        );

        $ortc = new Ortc(null, $client);
        $ortc->fetchServer();
    }

}