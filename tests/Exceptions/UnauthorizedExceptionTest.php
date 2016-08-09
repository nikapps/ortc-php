<?php
namespace Tests\Exceptions;

use Tests\TestCase;
use Nikapps\OrtcPhp\Exceptions\UnauthorizedException;

class UnauthorizedExceptionTest extends TestCase
{
    public function testImplements()
    {
        $exception = new UnauthorizedException;
        $this->assertEquals("Unauthorized Access", $exception->getMessage());
    }
}
