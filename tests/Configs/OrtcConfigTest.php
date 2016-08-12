<?php

namespace Tests\Configs;

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Tests\TestCase;

class OrtcConfigTest extends TestCase
{
    /**
     * @dataProvider providerAttributesDefault
     */
    public function testAttributesDefault($attribute, $value)
    {
        $ortcConfig = new OrtcConfig();

        $methodGet = 'get'.ucfirst($attribute);
        $this->assertEquals($value, $ortcConfig->{$methodGet}());
        $this->assertAttributeEquals($value, $attribute, $ortcConfig);
    }

    public function providerAttributesDefault()
    {
        return [
            ['balancerUrl', 'https://ortc-developers.realtime.co/server/2.1?appkey={APP_KEY}'],
            ['applicationKey', null],
            ['privateKey', null],
            ['authenticationPath', '/authenticate'],
            ['sendPath', '/send'],
            ['maxChunkSize', 700],
            ['batchPoolSize', 5],
            ['preMessageString', '{RANDOM}_{PART}-{TOTAL_PARTS}_'],
        ];
    }

    /**
     * @dataProvider providerSetAttributes
     */
    public function testSetAttributes($attribute, $value)
    {
        $ortcConfig = new OrtcConfig();

        $methodSet = 'set'.ucfirst($attribute);
        $ortcConfig->{$methodSet}($value);

        $methodGet = 'get'.ucfirst($attribute);
        $this->assertEquals($value, $ortcConfig->{$methodGet}());
        $this->assertAttributeEquals($value, $attribute, $ortcConfig);
    }

    public function providerSetAttributes()
    {
        return [
            ['balancerUrl', 'https://ortc-developers.realtime.co/server/2.1?appkey=123456'],
            ['applicationKey', 'abcdef'],
            ['privateKey', '98765'],
            ['authenticationPath', '/auth'],
            ['sendPath', '/enviar'],
            ['maxChunkSize', 100],
            ['batchPoolSize', 20],
            ['preMessageString', '{OTHER}_{PART}-{TOTAL_PARTS}_'],
        ];
    }

    public function testAttributeVerifySsl()
    {
        $ortcConfig = new OrtcConfig();

        $this->assertTrue($ortcConfig->isVerifySsl());
        $this->assertAttributeEquals(true, 'verifySsl', $ortcConfig);

        $ortcConfig->setVerifySsl(0);
        $this->assertFalse($ortcConfig->isVerifySsl());
        $this->assertAttributeEquals(false, 'verifySsl', $ortcConfig);
    }
}
