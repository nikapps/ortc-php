<?php

namespace Nikapps\OrtcPhp\Models\Responses;

class AuthResponse extends OrtcResponse
{
    /**
     * @var bool
     */
    private $failed = true;

    /**
     * @return bool
     */
    public function isFailed()
    {
        return $this->failed;
    }

    /**
     * @param bool $failed
     */
    public function setFailed($failed)
    {
        $this->failed = (bool) $failed;
    }
}
