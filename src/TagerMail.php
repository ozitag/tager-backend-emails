<?php

namespace OZiTAG\Tager\Backend\Mail;

class TagerMail
{
    public function sendEmail($to, $subject, $body, $isHTML = false)
    {
        echo 'Send Email to ' . $to . ': ' . $template;
        die;
    }

    public function sendEmailUsingTemplate($template, $params = [], $to = null)
    {
        echo 'Send Template: ' . $template;
        die;
    }
}
