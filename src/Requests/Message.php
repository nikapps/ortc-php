<?php
namespace Nikapps\Ortc\Requests;

class Message
{
    /**
     * Message
     *
     * @var string
     */
    private $message;
    /**
     * Channel
     *
     * @var string
     */
    private $channel;
    /**
     * @var string|null
     */
    private $token = null;

    /**
     * Encode to base64?
     *
     * @var bool
     */
    private $withBase64 = false;

    /**
     * Size of each chunk in bytes
     *
     * @var int
     */
    private $chunkSize = 700;

    /**
     * Message constructor.
     * @param string $message
     * @param string $channel
     * @param string $token
     */
    public function __construct($message = null, $channel = null, $token = null)
    {
        $this->message = $message;
        $this->channel = $channel;
        $this->token = $token;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set channel
     *
     * @param string $channel
     * @return $this
     */
    public function channel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Encode to base64. Suitable for UTF-8 characters or binary messages
     */
    public function withBase64()
    {
        $this->withBase64 = true;

        return $this;
    }

    /**
     * Don't encode the message to base64
     *
     * @return $this
     */
    public function withoutBase64()
    {
        $this->withBase64 = false;

        return $this;
    }

    /**
     * Set authentication token [optional]
     *
     * When you don't have private key & application key,
     * you should set authentication token which is authenticated
     * to specified channel
     *
     * @param string $token
     * @return $this
     */
    public function token($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Size of each chunk/frame in bytes
     *
     * @param int $chunkSize
     * @return $this
     */
    public function chunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }


    /**
     * Chunk the message
     *
     * ORTC says you must send at most 800 bytes for each api call (WHY ?!?)
     * So we should chunk the message
     *
     * @return array
     */
    public function toChunks()
    {
        $this->prepareMessage();
        $randomString = substr(sha1(uniqid('', true)), -8);
        $messageChunks = str_split($this->message, $this->chunkSize);
        $totalChunks = count($messageChunks);

        return array_map(
            function ($chunk, $part) use ($randomString, $totalChunks) {
                return [
                    'M' => sprintf('%s_%d-%d_%s', $randomString, $part + 1, $totalChunks, $chunk),
                    'C' => $this->channel,
                    'AT' => $this->token
                ];
            },
            $messageChunks,
            array_keys($messageChunks)
        );

    }

    /**
     * Prepare message for sending
     *
     * @return string
     */
    protected function prepareMessage()
    {
        return $this->message = $this->withBase64
            ? base64_encode($this->message)
            : $this->message;
    }
}