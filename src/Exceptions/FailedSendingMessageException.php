<?php
namespace Nikapps\Ortc\Exceptions;

class FailedSendingMessageException extends OrtcException
{
    protected $message = 'Error on sending async requests';

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     * @return $this
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }


}