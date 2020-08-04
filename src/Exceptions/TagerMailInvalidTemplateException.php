<?php

namespace OZiTAG\Tager\Backend\Mail\Exceptions;

class TagerMailInvalidTemplateException extends \Exception
{
    public function __construct($template, $errorMessage = null)
    {
        parent::__construct('Invalid Template "' . $template . '"' . ($errorMessage ? ' - ' . $errorMessage : ''));
    }
}
