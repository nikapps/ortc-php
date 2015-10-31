<?php
namespace Nikapps\Ortc\Tests\RequestModels;

use Nikapps\Ortc\Requests\Authenticate;
use Nikapps\Ortc\Tests\OrtcTest;

class AuthenticateTest extends OrtcTest
{

    /**
     * @test
     */
    function it_should_convert_to_array_correctly()
    {
        $authenticate = (new Authenticate())
            ->token('token')
            ->isPrivate()
            ->addChannel('channel-a', Authenticate::READ)
            ->addChannel('channel-b', Authenticate::WRITE)
            ->ttl(100);

        $array = $authenticate->toArray();

        $this->assertArraySubset(
            [
                'PVT' => 1,
                'TTL' => 100,
                'TP' => 2,
                'AT' => 'token',
                'channel-a' => 'r',
                'channel-b' => 'w'
            ],
            $array
        );
    }
}