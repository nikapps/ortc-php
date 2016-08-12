<?php

namespace Tests\Exceptions;

use Nikapps\OrtcPhp\Exceptions\BatchRequestException;
use Tests\TestCase;

class BatchRequestExceptionTest extends TestCase
{
    public function testImplements()
    {
        $exception = new BatchRequestException();
        $this->assertEquals('At least one request is failed', $exception->getMessage());

        $this->assertAttributeEquals(null, 'results', $exception);
        $this->assertEquals(null, $exception->getResults());

        $results = ['value' => 1];
        $exception->setResults($results);
        $this->assertAttributeEquals($results, 'results', $exception);
        $this->assertEquals($results, $exception->getResults());
    }
}
