<?php

namespace App\Exceptions;

final class BadRequestException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
