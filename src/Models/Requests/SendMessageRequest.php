<?php

namespace Nikapps\OrtcPhp\Models\Requests;

use Nikapps\OrtcPhp\Handlers\OrtcResponseHandler;
use Nikapps\OrtcPhp\Handlers\SendMessageResponseHandler;
use Ramsey\Uuid\Uuid;

class SendMessageRequest extends OrtcRequest
{
    /**
     * authentication token.
     *
     * @var string
     */
    private $authToken;

    /**
     * channel name.
     *
     * @var string
     */
    private $channelName;

    /**
     * message.
     *
     * @var string
     */
    private $message;

    /**
     * get url path (not base url).
     *
     * @return string
     */
    public function getUrlPath()
    {
        return $this->getOrtcConfig()->getSendPath();
    }

    /**
     * is post request or get request?
     *
     * @return bool
     */
    public function isPost()
    {
        return true;
    }

    /**
     * get post body.
     *
     * @return array
     */
    public function getPostData()
    {
        $chunks = $this->makeChunks();

        $postData = [];

        foreach ($chunks as $chunk) {
            $postData[] = [
                'AK' => $this->getOrtcConfig()->getApplicationKey(),
                'PK' => $this->getOrtcConfig()->getPrivateKey(),
                'AT' => $this->getAuthToken(),
                'C'  => $this->getChannelName(),
                'M'  => $chunk,
            ];
        }

        return $postData;
    }

    /**
     * split the message into chunks.
     *
     * @return array
     */
    protected function makeChunks()
    {
        $maxSize = $this->getOrtcConfig()->getMaxChunkSize();

        $chunks = str_split($this->getMessage(), $maxSize);
        $numberOfParts = count($chunks);

        $randomString = substr(sha1(Uuid::uuid4()->toString()), -8);

        for ($i = 0; $i < count($chunks); $i++) {
            $preString = strtr(
                $this->getOrtcConfig()->getPreMessageString(),
                [
                    '{RANDOM}'      => $randomString,
                    '{PART}'        => $i + 1,
                    '{TOTAL_PARTS}' => $numberOfParts,
                ]
            );

            $chunks[$i] = $preString.$chunks[$i];
        }

        return $chunks;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     *
     * @return $this
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * @param string $channelName
     *
     * @return $this
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;

        return $this;
    }

    /**
     * get response handler.
     *
     * @return OrtcResponseHandler
     */
    public function getResponseHandler()
    {
        return new SendMessageResponseHandler();
    }
}
