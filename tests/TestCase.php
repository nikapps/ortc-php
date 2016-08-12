<?php

namespace Tests;

use Mockery;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}
