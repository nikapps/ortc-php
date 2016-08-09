<?php
namespace Nikapps\OrtcPhp\Models\Responses;

class AuthResponse extends OrtcResponse
{

    /**
     * @var boolean
     */
    private $failed = true;

    /**
     * @return boolean
     */
    public function isFailed()
    {
        return $this->failed;
    }

    /**
     * @param boolean $failed
     */
    public function setFailed($failed)
    {
        $this->failed = (bool) $failed;
    }
}
