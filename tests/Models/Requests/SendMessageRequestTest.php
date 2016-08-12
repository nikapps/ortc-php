<?php

namespace Tests\Models\Requests;

use Mockery as m;
use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Requests\SendMessageRequest;
use Tests\TestCase;

class SendMessageRequestTest extends TestCase
{
    public function testImplementsDefault()
    {
        $ortcConfig = new OrtcConfig();
        $ortcConfig->setApplicationKey('FOoB1r');

        $request = new SendMessageRequest();
        $request->setOrtcConfig($ortcConfig);

        $this->assertInstanceOf('Nikapps\OrtcPhp\Models\Requests\OrtcRequest', $request);
        $this->assertEquals('/send', $request->getUrlPath());
        $this->assertTrue($request->isPost());
        $this->assertFalse($request->isUrlAbsolute());
        $this->assertInstanceOf('Nikapps\OrtcPhp\Handlers\SendMessageResponseHandler', $request->getResponseHandler());
    }

    /**
     * @dataProvider providerSetAndGetAttributes
     */
    public function testSetAndGetAttributes($attribute, $value)
    {
        $request = new SendMessageRequest();

        $methodSet = 'set'.ucfirst($attribute);
        $request->{$methodSet}($value);

        $methodGet = 'get'.ucfirst($attribute);
        $this->assertEquals($value, $request->{$methodGet}());
        $this->assertAttributeEquals($value, $attribute, $request);
    }

    public function providerSetAndGetAttributes()
    {
        return [
            ['channelName', 'CHANNEL_NAME'],
            ['authToken', 'abcdef'],
            ['message', 'foo bar'],
        ];
    }

    public function testGetPostData()
    {
        $ortcConfig = new OrtcConfig();
        $ortcConfig->setApplicationKey('abcede');
        $ortcConfig->setPrivateKey('123456');
        $ortcConfig->setMaxChunkSize(10);

        $request = new SendMessageRequest();
        $request->setOrtcConfig($ortcConfig);
        $request->setAuthToken('wxyz123');
        $request->setMessage('{message:foo bar}');
        $request->setChannelName('channel_name');

        $postData = $request->getPostData();
        $this->assertEquals(2, count($postData));

        $this->assertEquals(5, count($postData[0]));
        $this->assertEquals('abcede', $postData[0]['AK']);
        $this->assertEquals('123456', $postData[0]['PK']);
        $this->assertEquals('wxyz123', $postData[0]['AT']);
        $this->assertEquals('channel_name', $postData[0]['C']);
        $this->assertRegExp('/^[A-Za-z0-9]{8}_1-2_\{message:f$/', $postData[0]['M']);

        $this->assertEquals(5, count($postData[1]));
        $this->assertEquals('abcede', $postData[1]['AK']);
        $this->assertEquals('123456', $postData[1]['PK']);
        $this->assertEquals('wxyz123', $postData[1]['AT']);
        $this->assertEquals('channel_name', $postData[1]['C']);
        $this->assertRegExp('/^[A-Za-z0-9]{8}_2-2_oo bar\}$/', $postData[1]['M']);
    }
}
