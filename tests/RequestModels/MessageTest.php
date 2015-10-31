<?php
namespace Nikapps\Ortc\Tests\RequestModels;

use Nikapps\Ortc\Requests\Message;
use Nikapps\Ortc\Tests\OrtcTest;

class MessageTest extends OrtcTest
{

    /**
     * @test
     */
    function it_should_generate_correct_parameters()
    {
        $message = (new Message())
            ->message('single chunk message')
            ->channel('channel-a')
            ->withoutBase64()
            ->token('token');

        $chunk = $message->toChunks()[0];

        $this->assertEquals('token', $chunk['AT']);
        $this->assertEquals('channel-a', $chunk['C']);
        $text = substr($chunk['M'], 8);
        $this->assertEquals('_1-1_single chunk message', $text);
    }

    /**
     * @test
     */
    function it_should_generate_single_chunk()
    {
        $message = (new Message())
            ->message('single chunk message')
            ->channel('channel-a');

        $chunks = $message->toChunks();

        $this->assertCount(1, $chunks);
    }

    /**
     * @test
     */
    function it_should_encode_message_to_base64()
    {
        $string = 'single chunk message';
        $message = (new Message())
            ->message($string)
            ->withBase64()
            ->channel('channel-a');

        $chunk = $message->toChunks()[0];

        $text = substr($chunk['M'], 13);

        $this->assertEquals(base64_encode($string), $text);

    }

    /**
     * @test
     */
    function it_should_generate_three_chunks()
    {

        $strings = [
            str_repeat('a', 700),
            str_repeat('b', 700),
            str_repeat('c', 1)
        ];

        $message = (new Message())
            ->message(implode('', $strings))
            ->channel('channel-a')
            ->token('token')
            ->chunkSize(700);

        $chunks = $message->toChunks();

        $this->assertCount(3, $chunks);

        $randomString = substr($chunks[0]['M'], 0, 8);

        for ($i = 0; $i < count($chunks); $i++) {
            $this->assertEquals('token', $chunks[$i]['AT']);;
            $this->assertEquals('channel-a', $chunks[$i]['C']);
            $text = substr($chunks[$i]['M'], 8);
            $this->assertEquals('_' . ($i + 1) . '-3_' . $strings[$i], $text);
            $this->assertEquals($randomString, substr($chunks[$i]['M'], 0, 8));
        }
    }
}