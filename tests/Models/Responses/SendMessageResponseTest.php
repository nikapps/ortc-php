<?php
namespace Tests\Responses;

use Tests\TestCase;
use Nikapps\OrtcPhp\Models\Responses\SendMessageResponse;

class SendMessageResponseTest extends TestCase
{
    public function testImplements()
    {
        $response = new SendMessageResponse;
        $this->assertAttributeEquals(null, 'results', $response);
        $this->assertEquals(null, $response->getResults());

        $results = ['value' => 1];
        $response->setResults($results);
        $this->assertAttributeEquals($results, 'results', $response);
        $this->assertEquals($results, $response->getResults());
    }
}
