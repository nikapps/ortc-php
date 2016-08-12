<?php

namespace Tests\Exceptions;

use Nikapps\OrtcPhp\Exceptions\InvalidBalancerUrlException;
use Tests\TestCase;

class InvalidBalancerUrlExceptionTest extends TestCase
{
    public function testImplements()
    {
        $exception = new InvalidBalancerUrlException();
        $this->assertEquals('Balancer URL is invalid', $exception->getMessage());

        $this->assertAttributeEquals(null, 'url', $exception);
        $this->assertEquals(null, $exception->getUrl());

        $url = ['value' => 1];
        $exception->setUrl($url);
        $this->assertAttributeEquals($url, 'url', $exception);
        $this->assertEquals($url, $exception->getUrl());
    }
}
