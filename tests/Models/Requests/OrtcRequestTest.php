<?php
namespace Tests\Models\Requests;

use Mockery as m;
use Tests\TestCase;
use Nikapps\OrtcPhp\Configs\OrtcConfig;

class OrtcRequestTest extends TestCase
{
    public function testImplementsDefault()
    {
        $request = m::mock('Nikapps\OrtcPhp\Models\Requests\OrtcRequest[]');
        $ortcConfig = new OrtcConfig;
        
        $this->assertEquals($request, $request->setOrtcConfig($ortcConfig));
        $this->assertEquals($ortcConfig, $request->getOrtcConfig());

        $this->assertFalse($request->isUrlAbsolute());
    }
}
