<?php

namespace App\Exceptions;

final class ApiException extends \Exception
{
    public function __construct(
        string $message,
        public readonly int $status
    ) {
        parent::__construct($message);
    }
}
