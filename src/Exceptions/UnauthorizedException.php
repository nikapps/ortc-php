<?php
namespace Nikapps\Ortc\Exceptions;

class UnauthorizedException extends OrtcException
{
    protected $message = 'Not authorized to request an api call, check your credentials';
}