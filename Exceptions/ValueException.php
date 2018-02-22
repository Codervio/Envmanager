<?php

namespace Codervio\Envmanager\Exceptions;

use UnexpectedValueException;
use Throwable;

class ValueException extends UnexpectedValueException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}