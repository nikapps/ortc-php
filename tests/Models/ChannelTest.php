<?php

namespace Tests\Models;

use Nikapps\OrtcPhp\Models\Channel;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function testConstruct()
    {
        $channel1 = new Channel('NAME1');
        $this->assertEquals('NAME1', $channel1->getName());
        $this->assertEquals('r', $channel1->getPermission());

        $channel2 = new Channel('NAME_2', Channel::PERMISSION_WRITE);
        $this->assertEquals('NAME_2', $channel2->getName());
        $this->assertEquals('w', $channel2->getPermission());
    }

    public function testSetAndGetAttributes()
    {
        $channel = new Channel();
        $this->assertEquals($channel, $channel->setName('channel_name'));
        $this->assertEquals($channel, $channel->setPermission(Channel::PERMISSION_READ));

        $this->assertEquals('channel_name', $channel->getName());
        $this->assertEquals('r', $channel->getPermission());
    }

    public function testToString()
    {
        $channel = new Channel('foobar');
        $this->assertEquals('foobar', (string) $channel);
    }
}
