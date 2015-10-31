<?php
namespace Nikapps\Ortc\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Nikapps\Ortc\Config\Config;
use Nikapps\Ortc\Exceptions\FailedSendingMessageException;
use Nikapps\Ortc\Ortc;
use Nikapps\Ortc\Requests\Authenticate;
use Nikapps\Ortc\Requests\Message;

class OrtcSendMessageTest extends OrtcTest
{

    /**
     * @test
     */
    function it_should_send_message_request_correctly()
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

        $message = (new Message())
            ->message('single chunk message')
            ->channel('channel-a')
            ->withoutBase64()
            ->token('token');

        $this->assertTrue($ortc->send($message));

        /** @var Request $request */
        $request = $container[0]['request'];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            'http://server.com/send',
            $request->getUri()->__toString()
        );

        parse_str($request->getBody()->getContents(), $body);

        $randomString = substr($body['M'], 0, 8);

        $chunks = $message->toChunks();
        $chunks[0]['M'] = substr_replace($chunks[0]['M'], $randomString, 0, 8);

        $this->assertArraySubset(
            array_merge(
                $chunks[0],
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
    function it_should_send_multiple_message_chunks()
    {

        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('', 201),
                $this->makeResponse('', 201),
                $this->makeResponse('', 201)
            ],
            $container
        );

        $ortc = new Ortc(null, $client);
        $ortc->setBaseUrl('http://server.com');

        $message = (new Message())
            ->message(str_repeat('a', 150))
            ->channel('channel-a')
            ->withoutBase64()
            ->chunkSize(50)
            ->token('token');

        $this->assertTrue($ortc->send($message));

        $this->assertCount(3, $container);

    }

    /**
     * @test
     * @expectedException \Nikapps\Ortc\Exceptions\FailedSendingMessageException
     */
    function it_should_throw_an_exception_when_one_of_request_is_failed()
    {

        $container = [];
        $client = $this->makeClient(
            [
                $this->makeResponse('', 201),
                $this->makeResponse('', 201),
                $this->makeResponse('Unauthorized access!', 401),
                $this->makeResponse('', 201)
            ],
            $container
        );

        $ortc = new Ortc(null, $client);
        $ortc->setBaseUrl('http://server.com');

        $message = (new Message())
            ->message(str_repeat('a', 200))
            ->channel('channel-a')
            ->withoutBase64()
            ->chunkSize(50)
            ->token('token');

        $ortc->send($message);
    }

}