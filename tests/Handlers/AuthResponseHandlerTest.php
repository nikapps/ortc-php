<?php
namespace Tests\Handlers;

use Mockery as m;
use Tests\TestCase;
use Nikapps\OrtcPhp\Handlers\AuthResponseHandler;

class AuthResponseHandlerTest extends TestCase
{
    public function testImplementsAndHandle()
    {
        $handler = new AuthResponseHandler;
        $this->assertInstanceOf('Nikapps\OrtcPhp\Handlers\OrtcResponseHandler', $handler);

        $futureResponse = m::mock('FutureResponse');
        $response = $handler->handle($futureResponse);
        $this->assertInstanceOf('Nikapps\OrtcPhp\Models\Responses\AuthResponse', $response);
        $this->assertFalse($response->isFailed());
    }
}
