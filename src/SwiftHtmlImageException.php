<?php

namespace S4A;

class SwiftHtmlImageException extends \Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message, 0, null);
    }
}