<?php

namespace Tests\Models\Requests;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Channel;
use Nikapps\OrtcPhp\Models\Requests\AuthRequest;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    public function testImplementsDefault()
    {
        $ortcConfig = new OrtcConfig();

        $request = new AuthRequest();
        $request->setOrtcConfig($ortcConfig);

        $this->assertInstanceOf('Nikapps\OrtcPhp\Models\Requests\OrtcRequest', $request);
        $this->assertEquals('/authenticate', $request->getUrlPath());
        $this->assertFalse($request->isUrlAbsolute());
        $this->assertTrue($request->isPost());
        $this->assertInstanceOf('Nikapps\OrtcPhp\Handlers\AuthResponseHandler', $request->getResponseHandler());
    }

    /**
     * @dataProvider providerSetAndGetAttributes
     */
    public function testSetAndGetAttributes($attribute, $value)
    {
        $request = new AuthRequest();

        $methodSet = 'set'.ucfirst($attribute);
        $request->{$methodSet}($value);

        $methodGet = 'get'.ucfirst($attribute);
        $this->assertEquals($value, $request->{$methodGet}());
        $this->assertAttributeEquals($value, $attribute, $request);
    }

    public function providerSetAndGetAttributes()
    {
        return [
            ['channels', ['CHANNEL1', 'CHANNEL2']],
            ['authToken', 'abcdef'],
            ['expireTime', 3600],
        ];
    }

    public function testAttributePrivate()
    {
        $request = new AuthRequest();
        $this->assertFalse($request->isPrivate());

        $request->setPrivate(1);
        $this->assertTrue($request->isPrivate());
    }

    public function testGetPostData()
    {
        $ortcConfig = new OrtcConfig();
        $ortcConfig->setApplicationKey('abcede');
        $ortcConfig->setPrivateKey('123456');

        $request = new AuthRequest();
        $request->setOrtcConfig($ortcConfig);
        $request->setAuthToken('wxyz123');
        $request->setExpireTime(3600);
        $request->setChannels([new Channel('channel1', 'r'), new Channel('channel2', 'w')]);

        $expected = [
            'AT'       => 'wxyz123',
            'PVT'      => 0,
            'AK'       => 'abcede',
            'TTL'      => 3600,
            'PK'       => '123456',
            'TP'       => 2,
            'channel1' => 'r',
            'channel2' => 'w',
        ];

        $this->assertEquals($expected, $request->getPostData());
    }
}
