<?php

namespace App\Exceptions;

final class PageNotFoundException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
