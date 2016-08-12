<?php

namespace Tests\Models\Requests;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Requests\BalancerUrlRequest;
use Tests\TestCase;

class BalancerUrlRequestTest extends TestCase
{
    public function testImplementsDefault()
    {
        $ortcConfig = new OrtcConfig();
        $ortcConfig->setApplicationKey('FOoB1r');

        $request = new BalancerUrlRequest();
        $request->setOrtcConfig($ortcConfig);

        $this->assertInstanceOf('Nikapps\OrtcPhp\Models\Requests\OrtcRequest', $request);
        $this->assertEquals('https://ortc-developers.realtime.co/server/2.1?appkey=FOoB1r', $request->getUrlPath());
        $this->assertFalse($request->isPost());
        $this->assertEquals([], $request->getPostData());
        $this->assertTrue($request->isUrlAbsolute());
        $this->assertInstanceOf('Nikapps\OrtcPhp\Handlers\BalancerUrlResponseHandler', $request->getResponseHandler());
    }
}
