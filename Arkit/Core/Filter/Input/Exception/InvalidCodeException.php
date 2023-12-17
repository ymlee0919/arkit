<?php

namespace Arkit\Core\Filter\Input\Exception;

/**
 * This class handle the JWT token.
 */
class InvalidCodeException extends \Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return __CLASS__ . " :: [{$this->code}]: {$this->message}";
    }
}