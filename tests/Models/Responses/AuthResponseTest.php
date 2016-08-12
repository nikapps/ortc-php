<?php
namespace Tests\Responses;

use Tests\TestCase;
use Nikapps\OrtcPhp\Models\Responses\AuthResponse;

class AuthResponseTest extends TestCase
{
    public function testImplements()
    {
        $response = new AuthResponse;
        $this->assertAttributeEquals(true, 'failed', $response);
        $this->assertTrue($response->isFailed());

        $response->setFailed(1);
        $this->assertAttributeEquals(true, 'failed', $response);
        $this->assertTrue($response->isFailed());
    }
}
