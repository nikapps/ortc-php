<?php
namespace Tests\Responses;

use Tests\TestCase;
use Nikapps\OrtcPhp\Models\Responses\BalancerUrlResponse;

class BalancerUrlResponseTest extends TestCase
{
    public function testImplements()
    {
        $response = new BalancerUrlResponse;
        $this->assertAttributeEquals(null, 'url', $response);
        $this->assertEquals(null, $response->getUrl());

        $url = 'http://localhost';
        $response->setUrl($url);
        $this->assertAttributeEquals($url, 'url', $response);
        $this->assertEquals($url, $response->getUrl());
    }
}
