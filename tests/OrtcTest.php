<?php
namespace Nikapps\Ortc\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Nikapps\Ortc\Ortc;

class OrtcTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    function it_is_initializable()
    {
        $ortc = new Ortc();
        $this->assertInstanceOf(Ortc::class, $ortc);
    }

    /**
     * Generate mock client
     *
     * @param array $responses
     * @param array $container
     * @return Client
     */
    protected function makeClient(array $responses = [], &$container = [])
    {
        $mock = new MockHandler($responses);

        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::history($container));

        return new Client(['handler' => $handler]);
    }

    protected function makeResponse($body, $status = 200, array $headers = [])
    {
        return new Response($status, $headers, $body);
    }
}